<section class="sidebar">
    <ul class="sidebar-menu">

        <li class="<?php if (Request::segment(2) == '') echo "active"; ?>">
            <a href="{{ url('artmin') }}" rel="pages">
                <i class="fa fa-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <?php if (in_array(Auth::user()->roles, ['1', '2'])) { ?>
        <li class="<?php if (Request::segment(2) == 'slide-show') echo "active"; ?>">
            <a href="{{ url('artmin/slide-show') }}" rel="pages">
                <i class="fa fa-tv"></i>
                <span>Slide Show</span>
            </a>
        </li>
        <?php } ?>

        <?php
        if (in_array(Auth::user()->roles, ['1', '2', '8', '9'])) {
        ?>
            <li class="<?php if (Request::segment(2) == 'warranty') echo "active"; ?>">
                <a href="{{ url('artmin/warranty') }}" rel="pages">
                    <!-- <i class="fa fa-dashboard"></i> -->
                    <span>Customer Warranty</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'service') echo "active"; ?>">
                <a href="{{ url('artmin/service/request') }}" rel="pages">
                    <!-- <i class="fa fa-dashboard"></i> -->
                    <span>Service Request</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'installation') echo "active"; ?>">
                <a href="{{ url('artmin/installation/request') }}" rel="pages">
                    <!-- <i class="fa fa-dashboard"></i> -->
                    <span>Installation Request</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'quiz') echo "active"; ?>">
                <a href="{{ url('artmin/quiz') }}" rel="pages">
                    <!-- <i class="fa fa-dashboard"></i> -->
                    <span>APC Quiz</span>
                </a>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'csreport') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>CS Report</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/csreport/customer_products') }}" rel="pages"><i class="fa fa-circle-o"></i> Customer Product</a></li>
                </ul>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'product') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>Product</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/product/list') }}" rel="pages"><i class="fa fa-circle-o"></i> Products</a></li>
                    <li><a href="{{ url('artmin/product/categories') }}" rel="pages"><i class="fa fa-circle-o"></i> Categories</a></li>
                    <li><a href="{{ url('artmin/product/serialnumber') }}" rel="pages"><i class="fa fa-circle-o"></i> Serial Number</a></li>
                    <li><a href="{{ url('artmin/product/ordering') }}" rel="pages"><i class="fa fa-circle-o"></i> Ordering</a></li>
                    <!-- <li><a href="{{ url('artmin/product/warranty') }}" rel="pages"><i class="fa fa-circle-o"></i> Warranty</a></li> -->
                </ul>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'promotion') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>Promotion</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/promotion/tradein') }}" rel="pages"><i class="fa fa-circle-o"></i> Trade In</a></li>
                    <li><a href="{{ url('artmin/promotion/cashback') }}" rel="pages"><i class="fa fa-circle-o"></i> Cashback</a></li>
                    <li><a href="{{ url('artmin/promotion/specialvoucher') }}" rel="pages"><i class="fa fa-circle-o"></i> Special Voucher</a></li>
                    <li><a href="{{ url('artmin/promotion/luckydraw') }}" rel="pages"><i class="fa fa-circle-o"></i> Lucky Draw</a></li>
                </ul>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'troubleshoot') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>TroubleShoot</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/problem-initial') }}" rel="pages"><i class="fa fa-circle-o"></i> Problem Initial</a></li>
                    <li><a href="{{ url('artmin/problem-symptom') }}" rel="pages"><i class="fa fa-circle-o"></i> Problem Symptom</a></li>
                    <li><a href="{{ url('artmin/problem-defect') }}" rel="pages"><i class="fa fa-circle-o"></i> Problem Defect</a></li>
                    <li><a href="{{ url('artmin/problem-action') }}" rel="pages"><i class="fa fa-circle-o"></i> Problem Action</a></li>
                    <li><a href="{{ url('artmin/problem-taken') }}" rel="pages"><i class="fa fa-circle-o"></i> Problem Taken</a></li>
                </ul>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'faq') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>FAQ</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/faq/answer') }}" rel="pages"><i class="fa fa-circle-o"></i> Answer</a></li>
                    <li><a href="{{ url('artmin/faq/question-answer') }}" rel="pages"><i class="fa fa-circle-o"></i> Question Answer</a></li>
                </ul>
            </li>
            <li class="<?php if (Request::segment(2) == 'servicecenter') echo "active"; ?>">
                <a href="{{ url('artmin/servicecenter') }}" rel="pages">
                    <span>Service Center</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'storelocation') echo "active"; ?>">
                <a href="{{ url('artmin/storelocation') }}" rel="pages">
                    <span>Store</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'technician') echo "active"; ?>">
                <a href="{{ url('artmin/technician') }}" rel="pages">
                    <span>Technician</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'article') echo "active"; ?>">
                <a href="{{ url('artmin/article') }}" rel="pages">
                    <span>Article</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'product-knowledge') echo "active"; ?>">
                <a href="{{ url('artmin/product-knowledge') }}" rel="pages">
                    <span>Product Knowledge</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'gallery') echo "active"; ?>">
                <a href="{{ url('artmin/gallery') }}" rel="pages">
                    <span>Gallery</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'user') echo "active"; ?>">
                <a href="{{ url('artmin/user') }}" rel="pages">
                    <span>Users</span>
                </a>
            </li>
            <li class="treeview <?php if (Request::segment(2) == 'member') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span><i class="fa fa-users"></i>&nbsp;&nbsp;&nbsp;Member Management</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/member') }}" rel="pages"><i class="fa fa-user"></i> Master Member</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/member/point/list') }}" rel="pages"><i class="fa fa-gift"></i> Member Points</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/member/testimony/list') }}" rel="pages"><i class="fa fa-comments"></i> Member Testimony</a></li>
                </ul>
            </li>
            <li class="<?php if (Request::segment(2) == 'reset-password') echo "active"; ?>">
                <a href="{{ url('artmin/reset-password') }}" rel="pages">
                    <span>Reset Password</span>
                </a>
            </li>

            <li class="treeview <?php if (Request::segment(2) == 'whatsapp') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span><i class="fa fa-whatsapp"></i>&nbsp;&nbsp;&nbsp;Whatsapp</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/whatsapp/dashboard') }}" rel="pages"><i class="fa fa-bar-chart"></i> Dashboard</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/whatsapp/connect-wa') }}" rel="pages"><i class="fa fa-qrcode"></i> Connect Whatsapp</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/whatsapp/wa-msg-template') }}" rel="pages"><i class="fa fa-newspaper-o"></i> Template Message</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/contacts') }}" rel="pages"><i class="fa fa-users"></i> Contacts</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/whatsapp/wa-msg-blast') }}" rel="pages"><i class="fa fa-envelope"></i> Message Blast</a></li>
                </ul>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/whatsapp/wa-setting') }}" rel="pages"><i class="fa fa-gear"></i> Setting</a></li>
                </ul>
            </li>

            <li class="treeview <?php if (Request::segment(2) == 'product') echo "active"; ?>">
                <a href="#" rel="pages">
                    <span>Settings</span><i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li><a href="{{ url('artmin/settings/footer') }}" rel="pages"><i class="fa fa-circle-o"></i> Footer</a></li>
                </ul>
            </li>

        <?php
        } elseif (Auth::user()->roles == '3') {
        ?>
            <li class="<?php if (Request::segment(2) == 'warranty') echo "active"; ?>">
                <a href="{{ url('artmin/warranty') }}" rel="pages">
                    <!-- <i class="fa fa-dashboard"></i> -->
                    <span>Customer Warranty</span>
                </a>
            </li>
        <?php
        } ?>

        <?php
        if (Auth::user()->roles == '5' || Auth::user()->roles == '8') {
        ?>
            <li class="<?php if (Request::segment(2) == 'registerproductcustomer') echo "active"; ?>">
                <a href="{{ url('artmin/registerproductcustomer') }}" rel="pages">
                    <span>Register Product Customer</span>
                </a>
            </li>
            <li class="<?php if (Request::segment(2) == 'quiz') echo "active"; ?>">
                <a href="{{ url('artmin/quiz') }}" rel="pages">
                    <span>Quiz</span>
                </a>
            </li>

            <?php
            $users = DB::table('users')->where('id', Auth::user()->id)->first();
            $storeUsers = DB::table('ms_store_location_users')->select("id")->where('users_id', Auth::user()->id)->first();
            if (!empty($users->store_id) || !empty($storeUsers->id)) {
            ?>
                <li class="<?php if (Request::segment(2) == 'storesales') echo "active"; ?>">
                    <a href="{{ url('artmin/storesales') }}" rel="pages">
                        <span>Store Sales</span>
                    </a>
                </li>
            <?php
            }
            ?>

            <li class="<?php if (Request::segment(2) == 'product-knowledge') echo "active"; ?>">
                <a href="{{ url('artmin/product-knowledge') }}" rel="pages">
                    <span>Product Knowledge</span>
                </a>
            </li>
        <?php
        }
        ?>

    </ul>
</section>