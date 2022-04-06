<?php

/*
$debug      = 0;
$smtp       = \mailer\SMTP::get('gmail_ssl');
$auth       = [
    'username' => '',
    'password' => '',
];
$from       = [
    'email' => 'xxx@163.com',
    'alias' => 'xxx',
];
$reply      = '';
$to         = ['xxx@163.com'];
$cc         = [];
$bcc        = [];
$attachment = '';
$content    = [
    'subject' => '[TRCloud] Verification Code',
    'body'    => 'Your Verification Code: ' . rand(100000,999999),
];

$mailer = new \mailer\Mailer();
$bool   = $mailer->debug($debug)
                 ->smtp($smtp['host'],$smtp['secure'],$smtp['port'])
                 ->auth($auth['username'],$auth['password'])
                 ->from($from['email'],$from['alias'])
                 ->reply($reply)
                 ->to($to)
                 ->cc($cc)
                 ->bcc($bcc)
                 ->attachment($attachment)
                 ->subject($content['subject'])
                 ->body($content['body'])
                 ->send();
if ($bool) {
    return [
        'code' => 200,
        'msg'  => '发送成功',
        'data' => [],
    ];
} else {
    return [
        'code' => 500,
        'msg'  => '发送失败',
        'data' => $mailer->errmsg(),
    ];
}
*/

namespace mailer;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private $mailer;

    private $debug      = 0;

    private $host       = '';
    private $secure     = '';
    private $port       = '';

    private $username   = '';
    private $password   = '';
    
    private $fromEmail  = '';
    private $fromAlias  = '';
    
    private $reply      = '';
    
    private $to         = [];
    private $cc         = [];
    private $bcc        = [];
    
    private $attachment = '';

    private $subject    = '';
    private $body       = '';
    
    private $errmsg     = '';

    public function __construct()
    {
        $this->mailer = new PHPMailer(true);
    }

    /**
     * [调试级别]
     * @param  [type] $debug [0 关闭]
     * @param  [type] $debug [1 客户端命令]
     * @param  [type] $debug [2 客户端命令 | 服务器响应]
     * @param  [type] $debug [3 客户端命令 | 服务器响应 | 连接状态]
     * @param  [type] $debug [4 全调试信息]
     * @return [type]        [description]
     */
    public function debug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * [SMTP]
     * @param  [type] $host     [description]
     * @param  [type] $port     [description]
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function smtp($host, $secure = 'ssl', $port = 465)
    {
        $this->host   = $host;
        $this->secure = $secure;
        $this->port   = $port;
        return $this;
    }

    /**
     * [鉴权]
     * @param  [type] $username [description]
     * @param  [type] $password [description]
     * @return [type]           [description]
     */
    public function auth($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    public function from($email, $alias = '')
    {
        $this->fromEmail = $email;
        $this->fromAlias = $alias;
        return $this;
    }

    public function reply($email = '')
    {
        $this->reply = $email;
        return $this;
    }

    public function to($email)
    {
        if ( is_array($email) ) {
            foreach ($email as $key => $val) {
                $this->to[] = $val;
            }
        } else {
            $this->to[] = $email;
        }
        return $this;
    }

    public function cc($email)
    {
        if ( is_array($email) ) {
            foreach ($email as $key => $val) {
                $this->cc[] = $val;
            }
        } else {
            $this->cc[] = $email;
        }
        return $this;
    }

    public function bcc($email)
    {
        if ( is_array($email) ) {
            foreach ($email as $key => $val) {
                $this->bcc[] = $val;
            }
        } else {
            $this->bcc[] = $email;
        }
        return $this;
    }

    public function attachment($path)
    {
        $this->attachment = $path;
        return $this;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    public function body($body)
    {
        $this->body = $body;
        return $this;
    }

    public function send()
    {
        try {
            $this->mailer->SMTPDebug  = $this->debug;
            $this->mailer->isSMTP();
            $this->mailer->Host       = $this->host;
            $this->mailer->SMTPAuth   = true;
            $this->mailer->Username   = $this->username;
            $this->mailer->Password   = $this->password;
            $this->mailer->SMTPSecure = $this->secure;
            $this->mailer->Port       = $this->port;

            $this->mailer->setFrom($this->fromEmail, $this->fromAlias);

            empty($this->reply) || $this->mailer->addReplyTo($this->reply);

            foreach ($this->to as $key => $val) {
                $this->mailer->addAddress($val);
            }

            foreach ($this->cc as $key => $val) {
                $this->mailer->addCC($val);
            }

            foreach ($this->bcc as $key => $val) {
                $this->mailer->addBCC($val);
            }

            empty($this->attachment) || $this->mailer->addAttachment($this->attachment);

            $this->mailer->isHTML(true);
            $this->mailer->Subject = $this->subject;
            $this->mailer->Body    = $this->body;

            $this->mailer->send();

            return true;
        } catch (Exception $e) {
            $this->errmsg = $this->mailer->ErrorInfo;
            return false;
        }
    }

    public function errmsg()
    {
        return $this->errmsg;
    }
}