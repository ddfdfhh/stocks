<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function formateDate($v)
    {
        return $v ? date('j F,Y', strtotime($v)) : '';
    }
    public function mail($to, $subject, $body,$attachment_path=null,$attachment_name=null)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = config('mail.mailers.smtp.username'); //SMTP username
            $mail->Password =  config('mail.mailers.smtp.password'); //SMTP password
            $mail->SMTPSecure =  config('mail.mailers.smtp.encryption'); //Enable implicit TLS encryption
            $mail->Port = config('mail.mailers.smtp.port'); //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(config('mail.from.address'),config('mail.from.name'));
            $mail->addAddress($to); //Add a recipient

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            // $str=view('emails.registration_email',['user'=>auth()->user()])->render();
            $mail->Body = $body;
            if($attachment_path){
                $mail->addAttachment($attachment_path,$attachment_name);
            }
            $mail->send();
            return createResponse(true, 'Message has been sent');
        } catch (Exception $e) {
            return createResponse(false, $mail->ErrorInfo);

        }
    }
    public function selectSingle($table, $columns = [], $where = [])
    {
        $col = count($columns) > 0 ? implode(',', $columns) : '*';
        return \DB::table($table)->select($col)->where($where)->first();
    }
    public function countWhere($table, $where = [])
    {
        return \DB::table($table)->where($where)->count();
    }
    public function selectAll($table, $columns = [], $where = [])
    {
        $col = count($columns) > 0 ? implode(',', $columns) : '*';
        return \DB::table($table)->select($col)->where($where)->get();
    }
    public function selectAllPagination($table, $columns = [], $where = [])
    {
        $col = count($columns) > 0 ? implode(',', $columns) : '*';
        return \DB::table($table)->select($col)->where($where)->paginate();
    }
    public function insertTable($table, $col_val = [])
    {
        return \DB::table($table)->insert($col_val);

    }
    public function updateTable($table, $where = array(), $what = [])
    {
        $result = \DB::table($table)->where($where)->update($what);
        return $result;
    }
    public function updateIn($table, $whereIn = array(), $what = [])
    {
        $result = \DB::table($table)->whereIn($where)->update($what);
        return $result;
    }
    public function singleSet($table, $where = array(), $col = '', $inc = 0.0, $op = '+')
    {
        $q = \DB::table($table)->where($where);
        if ($op == '+') {
            $result = $q->increment($col, $inc);
        } else {
            $result = $q->decrement($col, $inc);
        }

        return $result;
    }
    public function multiResult($table, $columns = '', $where = array(), $limit = '', $offset = 0, $orderby = [], $whIn = ['col' => '', 'values' => []])
    {
        $col = count($columns) > 0 ? implode(',', $columns) : '*';

        $q = \DB::table($table)->select($col);
        if (!empty($where)) {
            $q = $q->where($where);
        }

        if (!empty($whIn)) {
            $q = $q->whereIn($whIn['col'], $whIn['values']);
        }

        if (!empty($limit)) {
            $q = $q->limit($limit, $offset);
        }

        if (!empty($orderby)) {
            $q = $q->orderBy($orderby['col'], $orderby['order']);
        }

        return $q->get();

        $result = $q->get()->getResult();
        return $result;
    }
    public function executeQuery($query)
    {
        return \DB::select($query);
    }
    public function joinResult($table1 = ['table' => '', 'column' => ''], $table2 = ['table' => '', 'column' => ''], $columns = '', $where = array(), $limit = '', $offset = 0, $orderby = null, $whIn = ['col' => '', 'values' => []])
    {
        $q = \DB::table($table1['table'] . " AS c1");
        ///  $col = count($columns) > 0 ? implode(',', $columns) : '*';
        //  dd($col);
        $q = $q->addSelect($columns);

        if (!empty($table2)) {
            $table1_col = $table1['column'];
            $table2_col = $table2['column'];
            $q = $q->leftJoin($table2['table'] . " AS c2", "c2." . $table2_col, "=", "c1." . $table1_col);
        }
        //$q->whereIn('c1.user_id',['456,234']);
        if (!empty($where)) {
            $q = $q->where($where);
        }

        // if (!empty($raw_where)) {
        //  $q= $q->where($raw_where, null, false);
        // }

        if (!empty($whIn['col'])) {
            $q = $q->whereIn($whIn['col'], $whIn['values']);
        }

        if (!empty($limit)) {
            $q = $q->limit($limit, $offset);
        }

        if (!empty($orderby)) {
            $q = $q->orderBy($orderby['col'], $orderby['order']);
        }

        return $q->get();

    }
    public function createResponse($success, $message, $redirect_url = null)
    {
        if ($success) {
            return response()->json(['success' => $success, 'message' => $message, 'url' => $redirect_url], 200);
        } else {
            return response()->json(['success' => $success, 'errors' => $message], 500);
        }

    }
    public function joinResultPage($table1 = ['table' => '', 'column' => ''], $table2 = ['table' => '', 'column' => ''], $columns = '', $where = array(), $limit = '', $offset = 0, $orderby = null, $whIn = ['col' => '', 'values' => []])
    {
        $q = \DB::table($table1['table'] . " AS c1");
        $col = count($columns) > 0 ? implode(',', $columns) : '*';
        $q = $q->select($columns);

        if (!empty($table2)) {
            $table1_col = $table1['column'];
            $table2_col = $join['column'];
            $q = $q->leftJoin("'" . $table2['table'] . " AS c2'", "'c2" . $table12_col . "'", "=", "'c1" . $table1_col . "'");
        }
        //$q->whereIn('c1.user_id',['456,234']);
        if (!empty($where)) {
            $q = $q->where($where);
        }

        // if (!empty($raw_where)) {
        //  $q= $q->where($raw_where, null, false);
        // }

        if (!empty($whIn)) {
            $q = $q->whereIn($whIn['col'], $whIn['values']);
        }

        if (!empty($limit)) {
            $q = $q->limit($limit, $offset);
        }

        if (!empty($orderby)) {
            $q = $q->orderBy($orderby['col'], $orderby['order']);
        }

        return $q->paginate();

    }
}
