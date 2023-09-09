<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class SendInvoiceMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $orderId;
    private $file_name;
    public function __construct($order_id, $file_name)
    {
        $this->orderId = $order_id;
        $this->file_name = $file_name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $order_id = $this->orderId;

        $row = \DB::table('create_order')->whereId($order_id)->first();

        $customer = \App\Models\Customer::with(['state', 'city'])->whereId($row->customer_id)->first();
        $settings = \DB::table('setting')->whereId(1)->first();

        $data['row'] = $row;
        $data['settings'] = $settings;
        $data['customer'] = $customer;
        if ($customer->email) {

            $str = view('emails.invoice', with($data))->render();
            try {
                $resp = $this->mail($customer->email, 'Invoice Email', $str, storage_path('pdf/' . $this->file_name), 'invoice.pdf');
               \DB::table('create_order')->whereId( $order_id)->update(['is_mail_sent'=>'Yes']);
                // $createorder->is_mail_Sent = 'Yes';
                // $createorder->save();
                \Log::error('email dispatched success for order if =====' . $order_id);

            } catch (\Exception $ex) {
                $createorder->mail_resp = $ex->getMessage();
                $createorder->save();

                \Log::error('email dispatch job error =====' . $ex->getMessage());
            }
        }

    }
    public function mail($to, $subject, $body, $attachment_path = null, $attachment_name = null)
    {
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = false; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = config('mail.mailers.smtp.host');
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = config('mail.mailers.smtp.username'); //SMTP username
            $mail->Password = config('mail.mailers.smtp.password'); //SMTP password
            $mail->SMTPSecure = config('mail.mailers.smtp.encryption'); //Enable implicit TLS encryption
            $mail->Port = config('mail.mailers.smtp.port'); //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            //Recipients
            $mail->setFrom(config('mail.from.address'), config('mail.from.name'));
            $mail->addAddress($to); //Add a recipient

            //Content
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = $subject;
            // $str=view('emails.registration_email',['user'=>auth()->user()])->render();
            $mail->Body = $body;
            if ($attachment_path) {
                $mail->addAttachment($attachment_path, $attachment_name);
            }
            $mail->send();
            return createResponse(true, 'Message has been sent');
        } catch (Exception $e) {
            return createResponse(false, $mail->ErrorInfo);

        }
    }
}
