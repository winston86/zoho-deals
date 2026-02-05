<?php

namespace Winston86\ZohoDeals\View\Components;

use Illuminate\View\Component;

class ZohoDeals extends Component
{

    public function render($tpl = 'zoho-deals::components.zoho-deals', $data = [])
    {
        return view($tpl, $data);
    }

}
