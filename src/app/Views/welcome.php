<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h1>Test Application</h1>
<p>For test and evaluation.</p>
<?php 
$environment_array = $this->data['environment_array'];
$table = new \CodeIgniter\View\Table();
if (!empty($environment_array)) {
  echo $table->generate($environment_array);
}
?>
<?= $this->endSection() ?>