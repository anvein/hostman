<?php

namespace anvi\manhost;

interface IHostManager
{
    public static function getColor($color);
    public static function viewHelp();

    public static function createHost($arParams);
    public static function deleteHost();

    public function checkPermSudo();

    public function parseinputParams($inputParams);
}