<?php 
    class Dashboard extends Controllers
    {
        public function __construct()
        {
            parent::__construct();
            session_start();
            if (empty($_SESSION['ingreso'])) {
                header('location: '.base_url().'/login');
            }
        }
        public function dashboard()
        {
           $data['page_id'] = 2;
           $data['page_tag'] = "Dashboard";
           $data['page_title'] = "Dashboard Farmacia Andaluz";
           $data['page_name'] = "dashboard";
           $data['page_functions_js'] = "functions_dashboard.js";
           $this->views->getView($this,"dashboard",$data);
        }      
    }
?>