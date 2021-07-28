<?php $this->load->view('templates/head'); ?>
<body class="bg-gradient-primary-a">
  	<div class="container">
    	<!-- Outer Row -->
    	<div class="row justify-content-center">
      		<div class="col-xl-10 col-lg-12 col-md-9">
		        <div class="card o-hidden border-0 shadow-lg my-5 card-login">
		          	<div class="card-body p-0">
		            <!-- Nested Row within Card Body -->
			            <div class="row">
			              	<div class="col-lg-6 d-none d-lg-block">
			              		<div id="container">
			              			<?php
							    		$json = json_decode(file_get_contents('assets/quotes.json'), true);
							    		//print_r($json);
							    		$rand = rand(0, sizeof($json)-1);
							    		$quote = $json[$rand];
							    	?>
									<h2><i class="fa fa-quote-left"> </i></h2>
									<div id="quoteContainer">
										<p id="quote_txt"><?= $quote['text']; ?></p>
										<p id="quoteGenius">- <?= $quote['author']; ?></p>			
									</div><!--end quoteContainer-->
								</div><!--end container-->
			              	</div>	
			              	<div class="col-lg-6">
			                	<div class="p-5">
				                  	<div class="text-center">
				                    	<h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
				                  	</div>
				                  	<?= form_open($form, array('class' => 'user'), ''); ?>
					                    <div class="form-group">
					                      	<input type="text" class="form-control form-control-user" name="username" id="username" placeholder="Username">
					                    </div>
					                    <div class="form-group">
					                      	<input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Password">
					                    </div>
					                    <button class="btn btn-primary btn-user btn-block">Login</button>
					                    <hr>
				                  	</form>
                  					<hr>
                				</div>
              				</div>
            			</div>
          			</div>
        		</div>
      		</div>
    	</div>
  	</div>


	<?php 
        $msg = $this->session->flashdata("msg");
        $msg_status = $this->session->flashdata("msg_status");
        $msg_title = $this->session->flashdata("msg_title");
        if(isset($msg)): 
    ?>
        <div class="alert <?= $msg_status; ?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 id="omg-error-txt"><?= $msg_title; ?><a class="anchorjs-link" href="#omg-error-txt"><span class="anchorjs-icon"></span></a></h4>
            <p><?php echo $msg; ?></p>
        </div>
    <?php endif; ?>
    <script type="text/javascript">
    	$(document).ready(function(){
    		
    	});
    </script>
<?php $this->load->view('templates/foot'); ?>