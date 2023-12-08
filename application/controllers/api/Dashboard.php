<?php
 require_once FCPATH . 'vendor/autoload.php';

 class Dashboard extends CI_Controller
 {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
        if (!$this->ion_auth->logged_in()) {
            redirect('auth');
        }
        $this->load->model('Dashboard_model', 'dashboard');
        $this->user = $this->ion_auth->user()->row();
    }

    public function index()
    {
        $user = $this->user;
        $data = [
            'user' => $user,
            'users' => $this->ion_auth->user()->row(),
        ];

        if ($this->ion_auth->is_admin()) {
            $data['info_box'] = $this->admin_box();
            $data['get_plot'] = $this->dashboard->get_max('pelanggan')->result();
            $data['get_plot2'] = $this->dashboard->get_max2('villa')->result();
        }
        $this->template->load('template/template', 'dashboard/dashboard', $data);
    }

    public function admin_box()
    {
        $box = [
            [
                'box' => 'light-blue',
                'total' => $this->dashboard->total('pelanggan'),
                'title' => 'Pelanggan',
                'icon' => 'user'
            ],
            [
                'box' => 'olive',
                'total' => $this->dashboard->total('villa'),
                'title' => 'Villa',
                'icon' => 'briefcase'
            ],
            [
                'box' => 'red',
                'total' => $this->dashboard->total('detail'),
                'title' => 'Detail',
                'icon' => 'retweet'
            ],
        ];
        $info_box = json_decode(json_encode($box), FALSE);
        return $info_box;
    }

    function graph()
    {
        $data['get_plot2'] = $this->dashboard->get_max('villa')->result();
        $data['get_plot'] = $this->dashboard->get_max('pelanggan')->result();
        $get_plot = json_decode(json_encode($plotting), FALSE);
        return $get_plot;
    }

    function _set_useragent()
    {
        if ($this->agent->is_mobile('iphone')) {
            $this->agent = 'iphone';
        } elseif ($this->agent->is_mobile()) {
            $this->agent = 'mobile';
        } else {
            $this->agent = 'desktop';
        }
    }
}
