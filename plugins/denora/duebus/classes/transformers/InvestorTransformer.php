<?php namespace Denora\Duebus\Classes\Transformers;

use Denora\Duebus\Models\Investor;

class InvestorTransformer {

    /**
     * @param Investor $investor
     *
     * @return array
     */
    static function transform(Investor $investor) {
        return [
            'id'                     => $investor->id,
            'investments_from'       => $investor->investments_from,
            'investments_to'         => $investor->investments_to,
            'businesses_invested_in' => $investor->businesses_invested_in,

            'created_at' => $investor->created_at,
            'updated_at' => $investor->updated_at,
        ];
    }

}