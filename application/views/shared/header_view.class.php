<?php

class Header_View extends Trident_Abstract_View
{
    public function render()
    {
?>
<!DOCTYPE html>
<html lang="he">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <?php $this->load_asset('bootstrap.min.css')?>
    <?php $this->load_asset('bootstrap-theme.min.css')?>
    <?php $this->load_asset('bootstrap-grid.min.css')?>
    <?php $this->load_asset('bootstrap-select.min.css')?>
    <?php $this->load_asset('bootstrap-fileinput.min.css')?>
    <?php $this->load_asset('bootstrap-tree.min.css')?>
    <?php if ($this->configuration->get('environment', 'rtl')) { $this->load_asset('bootstrap-rtl.min.css'); } ?>
    <?php $this->load_asset('font-awesome.min.css')?>
    <?php $this->load_asset('animate.min.css')?>
    <?php $this->load_asset('jquery.min.js')?>
    <?php $this->load_asset('bootstrap.min.js')?>
    <?php $this->load_asset('bootstrap-grid.min.js')?>
    <?php $this->load_asset('bootstrap-select.min.js')?>
    <?php $this->load_asset('bootstrap-fileinput.min.js')?>
    <?php $this->load_asset('bootstrap-fileinput-he.js')?>
    <?php $this->load_asset('bootstrap-tree.min.js')?>
    <?php $this->load_asset('bootstrap-validator.min.js')?>
    <title>Trident Framework Sample Application</title>
</head>
<body>
<?php
    }
}