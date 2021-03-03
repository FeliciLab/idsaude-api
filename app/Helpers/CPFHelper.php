<?php

namespace App\Helpers;

class CPFHelper
{
    public static function formataCpf($cpf)
    {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cpf);
    }
}
