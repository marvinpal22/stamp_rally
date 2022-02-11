<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;
class PasswordResetRequest extends Notification implements ShouldQueue
{
    use Queueable;
    protected $password;
    protected $email;

    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct($password,$email)
    {
        $this->password = $password;
        $this->email = $email;
    }
    /**
    * Get the notification's delivery channels.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
        return ['mail'];
    }
     /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
     public function toMail($notifiable)
     {

        // $url = url('http://172.16.20.169:8000/api/password/find/'.$this->username);
        return (new MailMessage)
        

            ->subject('Password Reset at Stamp Rally')
            ->greeting(new HtmlString('こんにちは! '.'<strong style="color:#0e6fb8">'.$this->email.',</strong>'))
            ->line('スタンプラリーアカウントのパスワードリセットリクエストを受け取ったため、このメールを受信して​​います。')
            ->line('---------------------------------')
            ->line(new HtmlString('<strong style="color:#0e6fb8">新しいパスワード:</strong>'))
            ->line(new HtmlString('<strong><center><label style="color:#ea5413 ;font-size:300%;">'.$this->password.'</label></center></strong>'))
            ->line('')
            ->line('---------------------------------')
            ->line('スタンプラリーアプリを開き、既存のユーザー名でログインします。上記の一時パスワードを入力します（コピーして貼り付けることもできます）。')
            
            ->withSwiftMessage(function ($message) {
                $message->getHeaders()->addTextHeader('X-Priority', '1');
                $message->getHeaders()->addTextHeader('X-Mailer', 'PHP'.phpversion());
                $message->getHeaders()->addTextHeader('MIME-Version', '1.0');


            });
    }
    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}