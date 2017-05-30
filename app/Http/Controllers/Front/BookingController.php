<?php namespace App\Http\Controllers\Front;

use App\Models;
use Illuminate\Http\Request;

class BookingController extends BaseFrontController
{
    public function __construct()
    {
        parent::__construct();
        $this->bodyClass = 'checkout';
    }

    public function postBooking(Request $request, Models\Booking $obj)
    {
        $validator = \Validator::make($request->all(), [
            'fullname' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[\+]?[(]?[0-9]{3}[)]?[-\s\.]?[0-9]{3}[-\s\.]?[0-9]{4,6}$/',
            'gender' => 'in:"mr","ms","mrs"',
            'address' => 'required',
            'start_date' => 'required|date',
            'travel_time' => 'required',
            'number_person' => 'required|numeric',
            'number_children' => 'numeric',
        ]);
        $url = redirect()->getUrlGenerator()->previous() .'#tab_book';
        if ($validator->fails()) {
            return redirect()->to($url)->withErrors($validator)->withInput();
        }
        $data = $request->all();
        $activity_type = (array) $request->activity_type;
        if(!empty($request->activity_type_other)) {
            $activity_type = array_prepend($activity_type, ["other" => $request->activity_type_other]);
        }
        $data['activity_type'] = json_encode($activity_type);
        $data['travel_type'] = json_encode($request->travel_type);
        $data['eat_type'] = json_encode($request->eat_type);
        $data['post_id'] = ($request->post_id != 0) ? $request->post_id : NULL;

        $booking = $obj->createItem($data);
        $data_booking = ['booking' => $booking];
        $from = [
           'name' => $booking['object']->name,
           'address' => $booking['object']->email
        ];
        $mail = $this->_sendFeedbackEmail('front.mails.order', 'Thông tin booking từ website ', $data_booking, $from);
        $request->session()->put('view_booking', '1');

        return redirect()->route('show.booking', $booking['object']->id);
    }

    public function getBooking(Request $request, $id) {
        $view_order = $request->session()->get('view_order');
        $this->dis['booking'] = Models\Booking::find($id);
        if(!$this->dis['booking']) return redirect('/');
        if ($request->session()->has('view_booking')) {
            $request->session()->forget('view_booking');
            return view('front.booking.show', $this->dis);
        } else {
            return redirect('/');
        }
    }

    public function getFormBooking() {
        $this->dis['activity_type'] = config('booking.default.activity_type');
        $this->dis['travel_type'] = config('booking.default.travel_type');
        $this->dis['type_eat'] = config('booking.default.type_eat');
        $this->_loadFrontMenu('', 'page', 'menu-left-on-search-page', 'list-group');
        return view('front.booking.index', $this->dis);
    }
}
