<?= $this->extend('default') ?>
<?= $this->section('content') ?>
<h1>Test Application</h1>
<p>For test and evaluation.</p>
<?php 
if (!empty($gae_environment))
{
  $table = new \CodeIgniter\View\Table(); 
  $table->setHeading("Key", "Value");
  echo $table->generate($gae_environment);
}
?>
<?= $this->endSection() ?>