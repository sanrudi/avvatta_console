<?php

namespace App\Exports\Subscriptions;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use App\Models\UserPayment;

class DailyTransactionExport implements  FromArray,WithHeadings, WithTitle, ShouldAutoSize, WithColumnFormatting, WithMapping
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function array(): array
    {
        return $this->transactions;
    }

    public function map($transactions): array
    {
        $create_at = date('Y-m-d H:i:s', strtotime($transactions['created_at']));
        $firstname = isset($transactions['user_payments_avvatta_users']['firstname']) ? $transactions['user_payments_avvatta_users']['firstname']:"";
        $lastname = isset($transactions['user_payments_avvatta_users']['lastname'])?$transactions['user_payments_avvatta_users']['lastname']:"";
        $customer = $firstname." ".$lastname;
        $title = isset($transactions['user_payments_subscriptions']['title'])?$transactions['user_payments_subscriptions']['title']:"";
        $amount = $transactions['amount'];
        $mode = $transactions['payment_mode'];
        return [ 
            $create_at,
            $customer,
            $title,
            $amount,
            $mode
        ];
    }

    public function headings(): array
    {
        return [
            'Date',
            'Customer',
            'Title',
            'Amount',
            'Mode'
        ];
    }

    public function title(): string
    {
        return 'video articles';
    }

    public function columnFormats(): array
    {
        return [
            //  'A' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            // 'D' => '#,##0',
            // 'E' => '#,##0',
        ];
    }
}