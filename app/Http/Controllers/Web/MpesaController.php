<?php


namespace App\Http\Controllers\Web;


use App\Http\Controllers\Controller;
use App\Models\System\ActionResponse;
use App\Models\System\DataStorage;
use App\Models\System\FileHandler;
use App\Models\System\RequestEncrypt;
use App\Models\System\SpinMobile;
use App\Models\Web\IzweApplication;
use App\Models\Web\MpesaStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;


class MpesaController extends Controller
{

    public function documents()
    {
//        return Storage::download('720526039c7ddee22606ee5a8cb2a1b2/d73f3baf9d4b7ea3208a0e6d44d2648b/1ade4ac8ca8c8c9b60de4d78515877e9.pdf');

        self::mpesaResponse();
        $response = [];
        $response['upload_error'] = 'y';
        $response['processing'] = false;
        $documents = MpesaStatement::where('user_id', Auth::user()->id)->get();
        $folder = DataStorage::data('folder', 'folder', Auth::user());
        if($folder['folder']!==false){
            $folder = $folder['folder']['folder_name'];
        }
        if (count($documents)) {
            foreach ($documents as $key => $document) {
                $response['data'][$key]['link'] = null;
                $response['data'][$key] = RequestEncrypt::decrypt($document->toArray());
                if(!is_null($folder)){
                    $response['data'][$key]['link'] = '720526039c7ddee22606ee5a8cb2a1b2/'.$folder.'/'.$response['data'][$key]['filename'].'.'.$response['data'][$key]['ext'];
                }

            }
        }

        $validateFileStatus = MpesaStatement::where('user_id', Auth::user()->id)->latest()->first();
        if (!is_null($validateFileStatus)) {
            if ($validateFileStatus->file_status === 'awaiting_mpesa_response') {
                $response['upload_error'] = 'n';
                $response['processing'] = true;
            }
        }
        return $response;
    }

    public function upload(Request $request)
    {

        $validateFileStatus = MpesaStatement::where('user_id', Auth::user()->id)->latest()->first();
        if (!is_null($validateFileStatus)) {
            if ($validateFileStatus->file_status === 'awaiting_mpesa_response') {
                $file = RequestEncrypt::decrypt($validateFileStatus->toArray());
                return ActionResponse::error('Sorry we cannot process your upload, we are currently processing your previous upload.', $file, false);
            }
        }

        $upload = [];
        $file = $request->file('file');
        if (isset($request->file)) {
            if ($file->isValid()) {
                $upload = (new FileHandler)->upload($request);
                if ($upload['success']) {
                    Session::put('upload', $upload);
                } else {
                    return $upload;
                }
            }
        }

        if (isset($request['encryption_code'])) {
            $file = Session::get('upload');
            if (!is_null($file)) {
                $analysis = (new SpinMobile)->analysis($request, $file);
                if ($analysis['success']) {
                    $appReq = [
                        'mpesa_document' => 1,
                        'application_status' => 'awaiting_mpesa_response'
                    ];

                    $app = IzweApplication::updateApplicationStatus($appReq);
                    if ($app['success']) {
                        return ActionResponse::success('File successfully uploaded.', [], true);
                    }
                    return $app;

                } else {
                    return $analysis;
                }
            }
        }
        return ActionResponse::error('Please upload a file before you can save', $upload, false);

    }

    public function mpesaResponse()
    {

        $request = '{
  "file_type": "MPESA",
  "phone": "2547xxxxxxxx",
  "id_number": "12345678",
  "bank_name": "",
  "account_number": "",
  "file_unique_id": "uuid4",
  "duration": "12",
  "json_data": {
    "document": {
      "name": "MPESA Statement",
      "status": "Completed",
      "date": "20/03/2023 02:40:10 PM"
    },
    "last_data": {
      "header": {
        "total_send_amt": 16701,
        "total_received_amt": 8227.54,
        "total_agent_deposit": 2670,
        "total_agent_withdrawal": 49063,
        "total_lipa_na_mpesa_paybill": 660,
        "total_lipa_na_mpesa_buygoods": "0.00",
        "total_paid_in": 68676.05,
        "total_paid_out": 75766.27,
        "total_paid_in_average": 22892.02,
        "total_paid_out_average": 25255.42,
        "total_others": 64735.96,
        "mpesa_balance": "0.00"
      },
      "body": {
        "customers": {
          "sent": {
            "count": 61,
            "highest": "2300.00",
            "lowest": "10.00",
            "total": "16423.00",
            "top": [
              {
                "phone": "2547******417",
                "name": "",
                "count": 6,
                "total": "1464.00",
                "highest": "527.00"
              },
              {
                "phone": "2547******736",
                "name": "",
                "count": 4,
                "total": "1110.00",
                "highest": "730.00"
              },
              {
                "phone": "2547******529",
                "name": "",
                "count": 4,
                "total": "900.00",
                "highest": "300.00"
              },
              {
                "phone": "2547******160",
                "name": "",
                "count": 4,
                "total": "2060.00",
                "highest": "800.00"
              },
              {
                "phone": "2547******158",
                "name": "",
                "count": 3,
                "total": "700.00",
                "highest": "330.00"
              },
              {
                "phone": "2547******690",
                "name": "",
                "count": 2,
                "total": "457.00",
                "highest": "230.00"
              },
              {
                "phone": "2547******348",
                "name": "",
                "count": 2,
                "total": "1150.00",
                "highest": "900.00"
              },
              {
                "phone": "2547******415",
                "name": "",
                "count": 2,
                "total": "600.00",
                "highest": "500.00"
              },
              {
                "phone": "2547******395",
                "name": "",
                "count": 1,
                "total": "430.00",
                "highest": "430.00"
              },
              {
                "phone": "2547******607",
                "name": "",
                "count": 1,
                "total": "40.00",
                "highest": "40.00"
              }
            ],
            "unique": 42
          },
          "received": {
            "count": 17,
            "highest": "1000.00",
            "lowest": "1.00",
            "total": "4205.00",
            "top": [
              {
                "phone": "2547******417",
                "name": "",
                "count": 6,
                "total": "1240.00",
                "highest": "300.00"
              },
              {
                "phone": "2547******002",
                "name": "",
                "count": 2,
                "total": "554.00",
                "highest": "327.00"
              },
              {
                "phone": "2547******722",
                "name": "",
                "count": 1,
                "total": "100.00",
                "highest": "100.00"
              },
              {
                "phone": "2547******702",
                "name": "",
                "count": 1,
                "total": "1.00",
                "highest": "1.00"
              },
              {
                "phone": "2547******409",
                "name": "",
                "count": 1,
                "total": "530.00",
                "highest": "530.00"
              },
              {
                "phone": "2547******567",
                "name": "",
                "count": 1,
                "total": "200.00",
                "highest": "200.00"
              },
              {
                "phone": "2547******956",
                "name": "",
                "count": 1,
                "total": "1000.00",
                "highest": "1000.00"
              },
              {
                "phone": "2547******118",
                "name": "",
                "count": 1,
                "total": "50.00",
                "highest": "50.00"
              },
              {
                "phone": "2547******489",
                "name": "",
                "count": 1,
                "total": "100.00",
                "highest": "100.00"
              },
              {
                "phone": "2547******199",
                "name": "",
                "count": 1,
                "total": "200.00",
                "highest": "200.00"
              }
            ],
            "unique": 11
          }
        },
        "airtime": {
          "count": 39,
          "highest": "100.00",
          "lowest": "5.00",
          "total": "1129.00",
          "last": "5.00"
        },
        "internet_bundles": {
          "count": 0,
          "highest": "0.00",
          "lowest": "0.00",
          "total": "0.00",
          "last": "0.00"
        },
        "fuliza": {
          "received": {
            "count": 71,
            "highest": "291.62",
            "lowest": "5.00",
            "total": "6699.32",
            "last": "20.00"
          },
          "paid": {
            "count": 33,
            "highest": "312.88",
            "lowest": "1.00",
            "total": "6553.10",
            "last": "0.00"
          }
        },
        "small_business": {
          "received": {
            "count": 0,
            "highest": "0.00",
            "lowest": "0.00",
            "total": "0.00",
            "last": "0.00"
          },
          "paid": {
            "count": 0,
            "highest": "0.00",
            "lowest": "0.00",
            "total": "0.00",
            "last": "0.00"
          },
          "transfer": {
            "count": 0,
            "highest": "0.00",
            "lowest": "0.00",
            "total": "0.00",
            "last": "0.00"
          }
        },
        "other_products": {
          "micro_sme_business": {
            "paid": {
              "count": 0,
              "highest": "0.00",
              "lowest": "0.00",
              "total": "0.00",
              "last": "0.00"
            }
          },
          "loan_soko": {
            "repayment": {
              "count": 0,
              "highest": "0.00",
              "lowest": "0.00",
              "total": "0.00",
              "last": "0.00"
            }
          }
        },
        "agent": {
          "withdraw": {
            "count": 5,
            "highest": "3180.00",
            "lowest": "250.00",
            "total": "7730.00",
            "last_draw": "2023-02-20 14:56:31",
            "top": [
              {
                "agent_no": "396043",
                "name": "Genericks Kenya class one investment nairobi by Genericks Kenya class one investment nairobiMK",
                "count": 1,
                "total": "3000.00",
                "highest": "3000.00"
              },
              {
                "agent_no": "2136821",
                "name": " SUKINDE COMMUNICATION Wakika enterprises Mosa market",
                "count": 1,
                "total": "1000.00",
                "highest": "1000.00"
              },
              {
                "agent_no": "289247",
                "name": "Dotevipa Enterprises Likoni ferry by Dotevipa Enterprises Likoni ferryEB",
                "count": 1,
                "total": "300.00",
                "highest": "300.00"
              },
              {
                "agent_no": "2067303",
                "name": "FORLAND ENTERPRISES LtdKaburengu Market Lowrence Mini",
                "count": 1,
                "total": "250.00",
                "highest": "250.00"
              },
              {
                "agent_no": "551018",
                "name": "Times dawali general shop lion webuye by Times dawali general shop lion webuye",
                "count": 1,
                "total": "3180.00",
                "highest": "3180.00"
              }
            ],
            "unique": 5
          },
          "deposit": {
            "count": 9,
            "highest": "800.00",
            "lowest": "50.00",
            "total": "2670.00",
            "last_draw": "2023-03-14 17:21:06",
            "top": [
              {
                "agent_no": "2136821",
                "name": " SUKINDE COMMUNICATION Wakika enterprises Mosa market",
                "count": 3,
                "total": "770.00",
                "highest": "460.00"
              },
              {
                "agent_no": "396043",
                "name": "Genericks Kenya class one investment nairobi by Genericks Kenya class one investment nairobiMK",
                "count": 2,
                "total": "650.00",
                "highest": "500.00"
              },
              {
                "agent_no": "2166692",
                "name": " Fugerssons agency Axmey shop mnazi",
                "count": 1,
                "total": "100.00",
                "highest": "100.00"
              },
              {
                "agent_no": "627827",
                "name": "Transworld Tours And Safaris Western Agrovet Masii Market Agg by Transworld Tours And Safaris Western Agrovet Masii Market AggMA",
                "count": 1,
                "total": "50.00",
                "highest": "50.00"
              },
              {
                "agent_no": "2016259",
                "name": " Kosfa Hardware NICHOLAS WABUYABO SHOP WAJIR GUEST HOUSE by Kosfa Hardware NICHOLAS WABUYABO SHOP WAJIR GUEST HOUSEYo",
                "count": 1,
                "total": "300.00",
                "highest": "300.00"
              },
              {
                "agent_no": "2184277",
                "name": " BLUE ROCK COMPANY MAMA JOY SHOP NAMGOI by BLUE ROCK COMPANY MAMA JOY SHOP NAMGOIjk",
                "count": 1,
                "total": "800.00",
                "highest": "800.00"
              }
            ],
            "unique": 6
          }
        },
        "fees": {
          "withdraw": "603.00",
          "customer_transfer": "278.00",
          "paybill": "0.00",
          "merchant": "0.00",
          "utility_reversal": "0.00",
          "unregistered_user": "0.00"
        },
        "paybill": {
          "sent": {
            "count": 18,
            "highest": "100.00",
            "lowest": "20.00",
            "total": "660.00",
            "top": [
              {
                "paybill_no": "323458",
                "name": "GREENLIGHT PLANET KENYA LTD",
                "count": 17,
                "total": "560.00",
                "highest": "80.00"
              },
              {
                "paybill_no": "632891",
                "name": "NGARISHA SACCO C2B",
                "count": 1,
                "total": "100.00",
                "highest": "100.00"
              }
            ],
            "classifications": {
              "Banks": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "MFIs": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Mobile Lenders": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Saccos": {
                "total": "100.00",
                "highest": "100.00",
                "lowest": "100.00",
                "highest_who": "NGARISHA SACCO C2B",
                "lowest_who": "NGARISHA SACCO C2B",
                "last_draw": "",
                "account_no": [
                  {
                    "name": "NGARISHA SACCO C2B",
                    "account": "11786188L106"
                  }
                ],
                "top": [
                  {
                    "last_draw": "2023-02-10 19:42:07",
                    "last": "100.00",
                    "highest": "100.00",
                    "count": 1,
                    "name": "NGARISHA SACCO C2B"
                  }
                ]
              },
              "Insurance": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Betting": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "International Remittance": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Education": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Fuel Stations": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Shopping Outlets": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Hotels": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Entertainment": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Healthcare": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Online Purchases": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "KPLC": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Water and Sewerage Services": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "DSTV or GOtv": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Zuku": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Faiba JTL": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Safaricom Home": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Beauty": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "Religion": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "account_no": [],
                "top": []
              },
              "General": {
                "total": "560.00",
                "highest": "80.00",
                "lowest": "20.00",
                "highest_who": "GREENLIGHT PLANET KENYA LTD",
                "lowest_who": "GREENLIGHT PLANET KENYA LTD",
                "last_draw": "2023-03-15 08:46:07",
                "account_no": [],
                "top": []
              }
            }
          },
          "received": {
            "count": 13,
            "highest": "38600.00",
            "lowest": "250.00",
            "total": "53501.73",
            "top": [
              {
                "paybill_no": "547701",
                "name": "National Bank Bulk Payment",
                "count": 8,
                "total": "3227.00",
                "highest": "527.00"
              },
              {
                "paybill_no": "632892",
                "name": "NGARISHA SACCO B2C  ",
                "count": 3,
                "total": "10674.73",
                "highest": "6400.00"
              },
              {
                "paybill_no": "300600",
                "name": "Equity Bulk Account",
                "count": 1,
                "total": "1000.00",
                "highest": "1000.00"
              },
              {
                "paybill_no": "3016467",
                "name": "IZWE LOANS KENYA  ",
                "count": 1,
                "total": "38600.00",
                "highest": "38600.00"
              }
            ],
            "classifications": {
              "Banks": {
                "total": "4227.00",
                "highest": "1000.00",
                "lowest": "250.00",
                "highest_who": "Equity Bulk Account",
                "lowest_who": "National Bank Bulk Payment",
                "last_draw": "2023-03-10 08:29:58",
                "top": [
                  {
                    "last_draw": "2023-03-10 08:29:58",
                    "last": "250.00",
                    "highest": "527.00",
                    "count": 8,
                    "name": "National Bank Bulk Payment"
                  },
                  {
                    "last_draw": "2023-01-23 10:04:50",
                    "last": "1000.00",
                    "highest": "1000.00",
                    "count": 1,
                    "name": "Equity Bulk Account"
                  }
                ]
              },
              "MFIs": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "Mobile Lenders": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "Saccos": {
                "total": "10674.73",
                "highest": "6400.00",
                "lowest": "854.73",
                "highest_who": "NGARISHA SACCO B2C  ",
                "lowest_who": "NGARISHA SACCO B2C  ",
                "last_draw": "",
                "top": [
                  {
                    "last_draw": "2023-02-18 14:27:24",
                    "last": "6400.00",
                    "highest": "6400.00",
                    "count": 3,
                    "name": "NGARISHA SACCO B2C  "
                  }
                ]
              },
              "Insurance": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "Betting": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "International Remittance": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "Online Purchases": {
                "total": 0,
                "highest": 0,
                "lowest": 0,
                "highest_who": "",
                "lowest_who": "",
                "last_draw": "",
                "top": []
              },
              "General": {
                "total": "38600.00",
                "highest": "38600.00",
                "lowest": "38600.00",
                "highest_who": "IZWE LOANS KENYA  ",
                "lowest_who": "IZWE LOANS KENYA  ",
                "last_draw": "",
                "top": []
              }
            }
          }
        },
        "buy_goods": {
          "count": 11,
          "highest": "32000.00",
          "lowest": "35.00",
          "total": "40865.00",
          "top": [
            {
              "till_no": "396043",
              "name": "Genericks Kenya class one investment nairobi",
              "count": 2,
              "total": "310.00",
              "highest": "250.00"
            },
            {
              "till_no": "363020",
              "name": "Mukmik Consultants Kamrembo shop Opp DCs Office Agg",
              "count": 1,
              "total": "290.00",
              "highest": "290.00"
            },
            {
              "till_no": "7985290",
              "name": "GRACE NANYAMA KHISA",
              "count": 1,
              "total": "40.00",
              "highest": "40.00"
            },
            {
              "till_no": "397602",
              "name": "Nicetech Ltd Nairobi 1",
              "count": 1,
              "total": "400.00",
              "highest": "400.00"
            },
            {
              "till_no": "329023",
              "name": "HARPS SOUNDS lunani general shop bgm township bungoma",
              "count": 1,
              "total": "550.00",
              "highest": "550.00"
            },
            {
              "till_no": "2135690",
              "name": "MAKUZEMBA co ltdARTEMIS BEATY SHOP ALONG CATHOLIC RDAgg",
              "count": 1,
              "total": "7000.00",
              "highest": "7000.00"
            },
            {
              "till_no": "2165815",
              "name": "Dibnu INVEST saaf quruh shop main rd moi girls sch",
              "count": 1,
              "total": "180.00",
              "highest": "180.00"
            },
            {
              "till_no": "209204",
              "name": "Jokakar Comms Opp Cooperative Bank Webuye",
              "count": 1,
              "total": "32000.00",
              "highest": "32000.00"
            },
            {
              "till_no": "7502346",
              "name": "WESTERN HARDWARE",
              "count": 1,
              "total": "35.00",
              "highest": "35.00"
            },
            {
              "till_no": "7678526",
              "name": "SHADRACK WANJALA NGOSASIA",
              "count": 1,
              "total": "60.00",
              "highest": "60.00"
            }
          ],
          "classifications": {
            "MFIs": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Saccos": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Betting": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Fuel Stations": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Shopping Outlets": {
              "total": "8020.00",
              "highest": "7000.00",
              "lowest": "180.00",
              "highest_who": "MAKUZEMBA co ltdARTEMIS BEATY SHOP ALONG CATHOLIC RDAgg",
              "lowest_who": "Dibnu INVEST saaf quruh shop main rd moi girls sch",
              "last_draw": "",
              "top": [
                {
                  "last_draw": "2023-03-10 11:09:23",
                  "last": "290.00",
                  "highest": "290.00",
                  "count": 1,
                  "name": "Mukmik Consultants Kamrembo shop Opp DCs Office Agg"
                },
                {
                  "last_draw": "2023-02-21 12:07:38",
                  "last": "550.00",
                  "highest": "550.00",
                  "count": 1,
                  "name": "HARPS SOUNDS lunani general shop bgm township bungoma"
                },
                {
                  "last_draw": "2023-02-04 11:14:36",
                  "last": "7000.00",
                  "highest": "7000.00",
                  "count": 1,
                  "name": "MAKUZEMBA co ltdARTEMIS BEATY SHOP ALONG CATHOLIC RDAgg"
                },
                {
                  "last_draw": "2023-01-28 19:04:40",
                  "last": "180.00",
                  "highest": "180.00",
                  "count": 1,
                  "name": "Dibnu INVEST saaf quruh shop main rd moi girls sch"
                }
              ]
            },
            "Hotels": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Entertainment": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Healthcare": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Beauty": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "Religion": {
              "total": 0,
              "highest": 0,
              "lowest": 0,
              "highest_who": "",
              "lowest_who": "",
              "last_draw": "",
              "top": []
            },
            "General": {
              "total": "32845.00",
              "highest": "32000.00",
              "lowest": "35.00",
              "highest_who": "Jokakar Comms Opp Cooperative Bank Webuye",
              "lowest_who": "WESTERN HARDWARE",
              "last_draw": "2023-03-13 14:37:46",
              "top": []
            }
          }
        },
        "kcb_mpesa": {
          "withdraw": {
            "count": 0,
            "highest": "0.00",
            "total": "0.00",
            "last": "",
            "last_amount": "0.00"
          },
          "deposit": {
            "count": 0,
            "highest": "0.00",
            "total": "0.00",
            "last": "",
            "last_amount": "0.00"
          },
          "loan": {
            "disburse": {
              "count": 0,
              "highest": "0.00",
              "total": "0.00",
              "last": "",
              "last_amount": "0.00"
            },
            "repayment": {
              "count": 0,
              "highest": "0.00",
              "total": "0.00",
              "last": "",
              "last_amount": "0.00"
            }
          }
        },
        "mshwari": {
          "withdraw": {
            "count": 0,
            "highest": "0.00",
            "total": "0.00",
            "last": "",
            "last_amount": "0.00"
          },
          "deposit": {
            "count": 0,
            "highest": "0.00",
            "total": "0.00",
            "last": "",
            "last_amount": "0.00"
          },
          "loan": {
            "disburse": {
              "count": 0,
              "highest": "0.00",
              "total": "0.00",
              "last": "",
              "last_amount": "0.00"
            },
            "repayment": {
              "count": 0,
              "highest": "0.00",
              "total": "0.00",
              "last": "",
              "last_amount": "0.00"
            }
          }
        },
        "hustler_fund": {
          "loan": {
            "disburse": {
              "count": 2,
              "highest": "800.00",
              "total": "1600.00",
              "last": "2022-12-31 09:04:37",
              "last_amount": "800.00"
            },
            "repayment": {
              "count": 4,
              "highest": "801.75",
              "total": "1485.17",
              "last": "2022-12-31 09:02:48",
              "last_amount": "801.75"
            }
          }
        },
        "other_lines": [
          "07******529"
        ],
        "peak_inflow_dates": [
          4,
          5,
          6,
          7,
          8
        ]
      },
      "information": {
        "customer_names": "FirstName SecondName ThirdName",
        "identity_number": "",
        "email": "email@gmail.com",
        "phone_number": "0723xxxxx",
        "statement_period": "20 Dec 2022 - 20 Mar 2023"
      },
      "remote_identifier": "",
      "boundaries": {
        "submission_age_in_days": 0,
        "processed_on_age_in_days": 0,
        "received_on": "2023-03-20 14:40:10",
        "duration_in_months": 3
      }
    },
    "classifications": [
      {
        "name": "Banks",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {
          "total": "4227.00",
          "highest": "1000.00",
          "lowest": "250.00",
          "highest_who": "Equity Bulk Account",
          "lowest_who": "National Bank Bulk Payment",
          "last_draw": "2023-03-10 08:29:58",
          "top": [
            {
              "last_draw": "2023-03-10 08:29:58",
              "last": "250.00",
              "highest": "527.00",
              "count": 8,
              "name": "National Bank Bulk Payment"
            },
            {
              "last_draw": "2023-01-23 10:04:50",
              "last": "1000.00",
              "highest": "1000.00",
              "count": 1,
              "name": "Equity Bulk Account"
            }
          ]
        }
      },
      {
        "name": "MFIs",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "Mobile Lenders",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "Saccos",
        "sent": {
          "total": 100,
          "highest": "100.00",
          "lowest": 0,
          "highest_who": "NGARISHA SACCO C2B",
          "lowest_who": "",
          "last_draw": "",
          "top": [
            {
              "last_draw": "2023-02-10 19:42:07",
              "last": "100.00",
              "highest": "100.00",
              "count": 1,
              "name": "NGARISHA SACCO C2B"
            }
          ]
        },
        "received": {
          "total": "10674.73",
          "highest": "6400.00",
          "lowest": "854.73",
          "highest_who": "NGARISHA SACCO B2C  ",
          "lowest_who": "NGARISHA SACCO B2C  ",
          "last_draw": "",
          "top": [
            {
              "last_draw": "2023-02-18 14:27:24",
              "last": "6400.00",
              "highest": "6400.00",
              "count": 3,
              "name": "NGARISHA SACCO B2C  "
            }
          ]
        }
      },
      {
        "name": "Insurance",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "Betting",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "International Remittance",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "Education",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Fuel Stations",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "Shopping Outlets",
        "sent": {
          "total": 8020,
          "highest": "7000.00",
          "lowest": 0,
          "highest_who": "MAKUZEMBA co ltdARTEMIS BEATY SHOP ALONG CATHOLIC RDAgg",
          "lowest_who": "",
          "last_draw": "",
          "top": [
            {
              "last_draw": "2023-03-10 11:09:23",
              "last": "290.00",
              "highest": "290.00",
              "count": 1,
              "name": "Mukmik Consultants Kamrembo shop Opp DCs Office Agg"
            },
            {
              "last_draw": "2023-02-21 12:07:38",
              "last": "550.00",
              "highest": "550.00",
              "count": 1,
              "name": "HARPS SOUNDS lunani general shop bgm township bungoma"
            },
            {
              "last_draw": "2023-02-04 11:14:36",
              "last": "7000.00",
              "highest": "7000.00",
              "count": 1,
              "name": "MAKUZEMBA co ltdARTEMIS BEATY SHOP ALONG CATHOLIC RDAgg"
            },
            {
              "last_draw": "2023-01-28 19:04:40",
              "last": "180.00",
              "highest": "180.00",
              "count": 1,
              "name": "Dibnu INVEST saaf quruh shop main rd moi girls sch"
            }
          ]
        },
        "received": {}
      },
      {
        "name": "Hotels",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "Entertainment",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "Healthcare",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "Online Purchases",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        }
      },
      {
        "name": "KPLC",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Water and Sewerage Services",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "DSTV or GOtv",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Zuku",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Faiba JTL",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Safaricom Home",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "account_no": [],
          "top": []
        },
        "received": {}
      },
      {
        "name": "Beauty",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "Religion",
        "sent": {
          "total": 0,
          "highest": 0,
          "lowest": 0,
          "highest_who": "",
          "lowest_who": "",
          "last_draw": "",
          "top": []
        },
        "received": {}
      },
      {
        "name": "General",
        "sent": {
          "total": 33405,
          "highest": "32000.00",
          "lowest": "20.00",
          "highest_who": "Jokakar Comms Opp Cooperative Bank Webuye",
          "lowest_who": "GREENLIGHT PLANET KENYA LTD",
          "last_draw": "2023-03-15 08:46:07",
          "top": []
        },
        "received": {
          "total": "38600.00",
          "highest": "38600.00",
          "lowest": "38600.00",
          "highest_who": "IZWE LOANS KENYA  ",
          "lowest_who": "IZWE LOANS KENYA  ",
          "last_draw": "",
          "top": []
        }
      }
    ],
    "income": {
      "data": [
        2670,
        4205,
        6699.32,
        4227,
        0,
        0,
        49274.73
      ],
      "labels": [
        "Agent Deposit: 4.0%",
        "Customers Received: 6.3%",
        "Fuliza Received: 10.0%",
        "Paybill Banks: 6.3%",
        "Paybill Betting: 0.0%",
        "Paybill Lenders: 0.0%",
        "Paybill Others: 73.5%"
      ]
    },
    "expenditure": {
      "data": [
        7730,
        40865,
        16423,
        6553.1,
        0,
        0,
        0,
        0,
        0,
        660,
        2010
      ],
      "labels": [
        "Agent Withdraw: 10.4%",
        "Buy Goods: 55.0%",
        "Customers Sent: 22.1%",
        "Fuliza Paid: 8.8%",
        "Utilities: 0.0%",
        "Banks: 0.0%",
        "Online Purchases: 0.0%",
        "Betting: 0.0%",
        "Mobile Lenders: 0.0%",
        "Paybill Others: 0.9%",
        "Others: 2.7%"
      ]
    },
    "other_lines": "07******529",
    "mobile_mfi_trends": {
      "MFIs": [],
      "MobileLenders": []
    },
    "income_flow": [
      {
        "name": "Inflow",
        "series": [
          {
            "name": "12/2022",
            "value": 9228.31
          },
          {
            "name": "01/2023",
            "value": 43692.62
          },
          {
            "name": "02/2023",
            "value": 12070.31
          },
          {
            "name": "03/2023",
            "value": 3684.81
          }
        ]
      }
    ],
    "expense_flow": [
      {
        "name": "Outflow",
        "series": [
          {
            "name": "12/2022",
            "value": 9136.31
          },
          {
            "name": "01/2023",
            "value": 11512.62
          },
          {
            "name": "02/2023",
            "value": 11412.53
          },
          {
            "name": "03/2023",
            "value": 2934.81
          }
        ]
      }
    ],
    "income_expense_tabulated": [
      {
        "Month": "03/2023",
        "Debits": 2934.81,
        "Credits": 3684.81,
        "Closing": 0
      },
      {
        "Month": "02/2023",
        "Debits": 11412.53,
        "Credits": 12070.31,
        "Closing": 220
      },
      {
        "Month": "01/2023",
        "Debits": 11512.62,
        "Credits": 43692.62,
        "Closing": 0
      },
      {
        "Month": "12/2022",
        "Debits": 9136.31,
        "Credits": 9228.31,
        "Closing": 0
      },
      {
        "Month": "Total",
        "Debits": 34996.27,
        "Credits": 68676.05,
        "Closing": 0
      },
      {
        "Month": "Average",
        "Debits": 11665.42,
        "Credits": 22892.02,
        "Closing": 0
      }
    ],
    "scores": {
      "m_score": {
        "score": 10,
        "loanable": 5000,
        "highest": 0,
        "risk_level": "Moderate",
        "deprecate": "Jan 2022"
      },
      "g_score": {
        "highest": 0,
        "loanable": 9500,
        "loanable_highest": 9500,
        "risk": "Moderate",
        "net_loanable_highest": 8600,
        "deprecate": "Jan 2022"
      },
      "d_score": {
        "mobile": {
          "net_score": 340,
          "gross_score": 340,
          "risk_level": "Major",
          "loanable": 1100
        },
        "long_term": {
          "net_score": 440,
          "gross_score": 440,
          "risk_level": "Major",
          "net_loanable_highest": 8600,
          "loanable_highest": 9500,
          "loanable": 9500,
          "highest": 0
        },
        "data": {
          "income_matrix": 150,
          "activity_level_matrix": 40,
          "impact_matrix": 0,
          "indebtedness_matrix": 150,
          "saf_based_loans_matrix": 0,
          "other_loans_matrix": 0,
          "long_term_other_loans_matrix": 100,
          "high_betting": "No",
          "healthcare_ratio_high": "No",
          "low_activity": "Yes",
          "history_of_repaying_other_mobile_loans": "No",
          "debt_to_income_ratio_high": "No"
        }
      }
    }
  },
  "ORGCODE": "IZWE",
  "ORGNAME": "IZWE Kenya"
}';

        return $request;
    }


}
