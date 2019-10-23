<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

interface JsonRequestParserService
{
    public function parse(Request $request): array;
}
