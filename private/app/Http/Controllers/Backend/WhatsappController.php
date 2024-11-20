<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class WhatsappController extends Controller
{

    public function dashboard () {

        $periode = [
            'today' => 'Today',
            'yesterday' => 'Yesterday',
            'current_month' => 'Current Month',
            'last_month' => 'Last Month',
            'current_year' => 'Current Year',
            'last_year' => 'Last Year',
        ];
        $status = [
            'waiting' => 'Waiting',
            'sent' => 'Sent',
            'cancel' => 'Cancel',
            'failed' => 'Failed'
        ];
        $data['colors'] = [
            'waiting' => 'bg-pink',
            'sent' => 'bg-cyan',
            'cancel' => 'bg-light-green',
            'failed' => 'bg-orange'
        ];
        $data['status'] = $status;
        $data['periode'] = $periode;

        $data['messages'] = [
            'waiting' => 0,
            'sent' => 0,
            'cancel' => 0,
            'failed' => 0,
        ];
        // foreach($periode as $key_per => $val_per) {
        //     foreach($status as $key_sts => $val_sts) {
        //         $data['messages'][$key_per][$key_sts] = 11;
        //     }
        // }

        return view('backend.whatsapp.dashboard', $data);
    }

    public function message_count ($period) {

        $sql = 'SELECT status, COUNT(status) AS total FROM wa_messages_recipients';

        $date_format = "%Y%m%d";
        $deduction = (str_contains($period, 'last') || $period == 'yesterday') ? 1 : 0;

        if (str_contains($period, 'month')) {
            $date_format = "%Y%m";
        } else if (str_contains($period,'year')) {
            $date_format = "%Y";
        }

        $sql .= ' WHERE DATE_FORMAT(process_date, "' . $date_format . '") = (CASE WHEN "' . $date_format . '" = "%Y%m" AND MONTH(NOW())=1 THEN CONCAT(DATE_FORMAT(NOW(), "%Y")-1, 13-' . $deduction . ') ELSE DATE_FORMAT(NOW(), "' . $date_format . '")-' . $deduction . ' END) ';
        $data = DB::select($sql . ' GROUP BY status;');

        return ['success' => true, 'message' => '', 'data' => $data];
    }

    public function connectWhatsapp()
    {
        // $data['subject'] = DB::table('footer_subject')->get();
        // $qrcode = QrCode::size(500)
        //     ->format('svg')
        //     ->generate($gen_str, storage_path('app/public/qrcodes/'.str_slug($tasks['title']).'.svg'));

        $session = DB::table('wa_session')->where('client_id', 'artugocs1')->first();

        $data['session'] = $session;
        return view('backend.whatsapp.connect', $data);
    }

    public function waMsgTemplate()
    {
        $template = DB::table('wa_messages')->orderBy('id', 'desc')->limit(300);

        if (isset($_GET['description']) && !empty($_GET['description'])) {
            $template->where('description', 'like', '%' . $_GET['description'] . '%');
        }

        if (isset($_GET['content']) && !empty($_GET['content'])) {
            $template->where('content', 'like', '%' . $_GET['content'] . '%');
        }

        $data['template'] = $template->get();

        return view('backend.whatsapp.template', $data);
    }

    public function waMsgTemplateJson(Request $request)
    {
        $keywords = $request->get('q') ?? '';
        $qb = DB::table('wa_messages')->select(['id', 'description', 'content'])->where('description', 'LIKE', '%' . $keywords . '%')->orWhere('content', 'LIKE', '%' . $keywords . '%');
        return $qb->get();
    }

    public function waMsgTemplateForm($template_id)
    {
        if ($template_id !== 'new') {
            $template = DB::table('wa_messages')->where('id', $template_id)->first();
            $data['template'] = $template;
        } else {
            $data['template'] = json_decode(json_encode(['id' => 0, 'description' => '', 'content' => '']));
        }
        return view('backend.whatsapp.template-form', $data);
    }

    public function waMsgTemplateSaveEdit(Request $request)
    {
        if ($request->input('id') > 0) {
            DB::table('wa_messages')->where('id', $request->input('id'))->update([
                'description' => $request->input('description'),
                'content' => $request->input('content')
            ]);
        } else {
            $id = DB::table('wa_messages')->insertGetId(array(
                'description' => $request->input('description'),
                'content' => $request->input('content')
            ));
            return redirect('artmin/whatsapp/wa-msg-template/' . $id);
        }
    }

    public function waMsgBlast(Request $request)
    {
        $data['queue'] = [];
        if ($request->ajax()) {
            $qb = DB::table('wa_messages_queue')
            ->select('wa_messages_queue.*', 'wa_messages.description', 'wa_messages.content')
            ->join('wa_messages', 'wa_messages.id', '=', 'wa_messages_queue.message_id');
            return DataTables::of($qb)->toJson();
        }

        return view('backend.whatsapp.message-blast', $data);
    }

    public function waMsgBlastDetail($queue_id)
    {

        $data['templates'] = [];

        if ($queue_id !== 'new') {
            $data['statusAction'] = 'update';
            $data['queue'] = DB::table('wa_messages_queue')
            ->select('wa_messages_queue.*', 'wa_messages.description')
            ->join('wa_messages', 'wa_messages.id', '=', 'wa_messages_queue.message_id')
            ->where('wa_messages_queue.id', $queue_id)->first();

            // $data['recipients'] = DB::table('wa_messages_recipients')->where('queue_id', $queue_id)->orderBy('name', 'asc')->get();
            $data['templates'] = DB::table('wa_messages')->where('id', $data['queue']->message_id)->get();
        } else {
            $data['statusAction'] = 'insert';
            $data['queue'] = json_decode(json_encode(array('id' => 0, 'message_id' => '', 'status' => 'draft', 'schedule_date' => date('Y-m-d H:i'), 'description' => '')));
            // $data['recipients']= array();
        }

        return view('backend.whatsapp.message-blast-form', $data);
    }

    public function waMsgBlastPost(Request $request)
    {

        $data = $request->input();

        $id = $data['id'];

        unset($data['_token']);
        unset($data['id']);

        if (isset($data['schedule_date'])) {
            $data['schedule_date'] = date('Y-m-d H:i:s', strtotime(str_replace('/','-', $data['schedule_date'])));
        }

        if (intval($id) === 0) {
            $id = DB::table('wa_messages_queue')->insertGetId($data);
        } else {
            DB::table('wa_messages_queue')->where('id', $id)->update($data);
        }

        if ($request->ajax()) {
            return ['success' => true, 'message' => 'Data template telah diupdate.'];
        } else {
            return redirect('artmin/whatsapp/wa-msg-blast/' . $id);
        }
    }

    public function waMsgBlasRecipients($queue_id)
    {
        $qb = DB::table('wa_messages_recipients')->where('queue_id', $queue_id);
        return DataTables::of($qb)->toJson();
    }

    public function waMsgBlasRecipientsPost(Request $request)
    {

        $id = $request->input('id');
        $id_contacts_selected = json_decode($request->input('contacts_selected'));
        $contacts_selected = DB::table('contacts')->select([DB::raw($id . ' AS queue_id'), 'contacts.name', 'contacts.phone AS number', DB::raw('"waiting" AS status')])
        ->leftJoin('wa_messages_recipients', 'wa_messages_recipients.number', '=', 'contacts.phone', 'and', 'wa_messages_recipients.queue_id', '=', $id)
        ->where('contacts.status', 'active')
        ->whereNull('wa_messages_recipients.number')
        ->whereIn('contacts.id', $id_contacts_selected)
        ->get()
        ->toArray();

        foreach($contacts_selected as $key => $val) {
            DB::table('wa_messages_recipients')->insert([
                'queue_id' => $val->queue_id,
                'name' => $val->name,
                'number' => $val->number,
                'status' => $val->status,
            ]);
        }

        $req_contact_count = count($id_contacts_selected);
        $added_contact_count = count($contacts_selected);

        $success = true;
        $message = $added_contact_count . ' penerima pesan telah ditambahkan.';
        if ($added_contact_count == 0 && $req_contact_count > 0) {
            $success = false;
            $message = 'Penerima yang anda pilih sudah ditambahkan sebelumnya!';
        } else if ($added_contact_count > 0 && $req_contact_count != $added_contact_count) {
            $success = true;
            $message = $added_contact_count . ' penerima berhasil ditambahkan dan ' . ($req_contact_count - $added_contact_count) . ' penerima sudah ditambahkan sebelumnya.';
        }

        return ['success' => $success, 'message' => $message];

    }

    public function waMsgBlastDelete(Request $request)
    {

        $id = $request->input('id');

        $queue = DB::table('wa_messages_queue')->select('status')->where('id', $id)->first();
        if (!$queue) {
            return ['success' => false, 'message' => 'Data tidak ditemukan.'];
        }
        if ($queue->status !== 'waiting') {
            return ['success' => false, 'message' => 'Hanya status waiting yang dapat dihapus.'];
        }

        DB::table('wa_messages_queue')->where('id', $id)->delete();
        DB::table('wa_messages_recipients')->where('queue_id', $id)->delete();

        return ['success' => true, 'message' => 'Data template telah dihapus.'];
    }

    public function waSetting(Request $request)
    {
        $data['setting'] = DB::table('wa_setting')->first();
        $data['wa_clients'] = DB::table('wa_session')->get();
        return view('backend.whatsapp.setting', $data);
    }

    public function waSettingPost(Request $request)
    {
        $id = $request->input('id');

        $data = [
            'wa_client_id' => $request->input('wa_client_id'),
            'send_interval' => $request->input('send_interval'),
            'send_limit' => $request->input('send_limit'),
        ];

        if ($id > 0) {
            DB::table('wa_setting')->where('id', $id)->update($data);
        } else {
            DB::table('wa_setting')->insert($data);
        }
        return ['success' => true, 'message' => 'Data setting telah disimpan.'];
    }

}
