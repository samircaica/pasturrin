<?php

	class TestRest  extends Rest {

		public function index() {
			$this->response('OK', 200);
		}

		public function productos() {
			$result = array();
			$this->products = Product::all();
			
			foreach($this->products as $product) {
				array_push($result, $product->to_json());
			}
			$this->response($this->json($result), 200);
		}

	}
?>