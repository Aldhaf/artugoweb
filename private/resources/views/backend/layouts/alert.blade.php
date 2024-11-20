<?php
if(Session::has('success')){
?>
<div class="alert alert-success">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?= Session::get('success') ?>
</div>
<?php

}
else if(Session::has('error')){
?>
<div class="alert alert-danger">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?= Session::get('error') ?>
</div>
<?php
}
?>
