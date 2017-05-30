<?php namespace App\Http\Controllers\Front;

use App\Models;
use Illuminate\Http\Request;

class GlobalActionsController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'product';
    }

    public function postContactUs(Request $request, Models\Contact $object)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
            'content' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $googleCaptchaResponse = $request->get('g-recaptcha-response', null);
        if (!_validateGoogleCaptcha($googleCaptchaResponse)) {
            return $this->_responseAutoDetect($request, trans('captcha.error'), true, 500, 'error', true);
        }

        $data = $request->all();
        if (isset($data['content'])) {
            $data['content'] = nl2br($data['content']);
        }
        $result = $object->fastEdit(_stripTags($data), true);
        $from = [
            'name' => $data['name'],
            'address' => $data['email']
        ];
        $mail = $this->_sendFeedbackEmail('front.mails.contact', $request->subject, $data, $from);
        $errorCode = ($result['error']) ? 500 : 200;
        $messageType = ($result['error']) ? 'error' : 'success';
        return $this->_responseAutoDetect($request, $result['message'], $result['error'], $errorCode, $messageType, true);
    }

    public function postMessage(Request $request, Models\Contact $object)
    {
        $validator = \Validator::make($request->all(), [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'content' => 'required|min:2',
        ]);
        if ($validator->fails()) {
            $message = "Les informations ci-dessous (marquées d'une astérisque) sont absentes ou incorrectes.";
            return $this->_responseRedirect($message, $type = 'error', $error = true, $withOldInputWhenError = true);
        }

        $data = $request->all();
        if (isset($data['content'])) {
            $data['content'] = nl2br($data['content']);
        }
        $subject = 'Laissez un message';
        $data['subject'] = $subject;
        $data['phone'] = null;
        $result = $object->fastEdit(_stripTags($data), true);
        $from = [
            'name' => $data['name'],
            'address' => $data['email']
        ];
        $mail = $this->_sendFeedbackEmail('front.mails.contact', $subject, $data, $from);
        $errorCode = ($result['error']) ? 500 : 200;
        $messageType = ($result['error']) ? 'error' : 'success';
        return $this->_responseAutoDetect($request, $result['message'], $result['error'], $errorCode, $messageType, true);
    }

    public function postSubscribeEmail(Request $request, Models\SubscribedEmails $object)
    {
        $result = $object->fastEdit(_stripTags($request->all()), true);
        $errorCode = ($result['error']) ? 500 : 200;
        $messageType = ($result['error']) ? 'error' : 'success';
        return $this->_responseAutoDetect($request, $result['message'], $result['error'], $errorCode, $messageType);
    }
}
