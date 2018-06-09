<?php $this->load->view('administrator/header'); 
    $this->load->view('administrator/sidebar');
  
?>  
<div class="col-md-10 col-sm-10 col-xs-12 padding_zero">
  
    	<div class="col-md-11 col-sm-12 col-xs-12">
    <h2 class="page-header"><?php echo ('Dashboard'); ?> <small><?php echo ('Latest Activity'); ?></small></h2>
    </div>
    
    
    <div class="col-md-11 col-sm-12 col-xs-12">
    <div class="col-md-3 col-sm-4 col-xs-12">
   		<div class="panel text-center no-border bg-color-green">
			<div class="panel-body">
    		<a class="link" href="<? echo base_url('administrator/members/rider');?>" ><?php echo $total_riders; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-green">
            <?php echo 'Total Riders '; ?>
          </div>
         </div>
        </div>
          
      	<div class="col-md-3 col-sm-4 col-xs-12">
      		<div class="panel text-center no-border bg-color-pink">
			<div class="panel-body">
    		<a class="link" href="<?php echo base_url('administrator/members/Driver'); ?>" ><?php echo $total_drivers; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-pink">
            <?php echo 'Total Drivers '; ?>
          </div>
         
         </div>
       </div>
    
          	<div class="col-md-3 col-sm-4 col-xs-12">
      		<div class="panel text-center no-border bg-color-pink">
			<div class="panel-body">
    		<a class="link" href="<?php echo base_url('administrator/members/rider?status=today'); ?>" ><?php echo $today_Riders; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-violet">
            <?php echo 'Total Today Riders '; ?>
          </div>
         
         </div>
       </div>
    
              	<div class="col-md-3 col-sm-4 col-xs-12">
      		<div class="panel text-center no-border bg-color-pink">
			<div class="panel-body">
    		<a class="link" href="<?php echo base_url('administrator/members/driver?status=today'); ?>" ><?php echo $today_Driver; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-litegreen">
            <?php echo 'Total Today Drivers '; ?>
          </div>
         
         </div>
       </div>
    	
    	<div class="col-md-3 col-sm-4 col-xs-12">
      		<div class="panel text-center no-border bg-color-pink">
			<div class="panel-body">
    		<a class="link" href="<?php echo base_url('administrator/payment/transaction'); ?>"> <?php echo $total_trips; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-blue">
            <?php echo 'Total Trips '; ?>
          </div>
         
         </div>
       </div>
    	
    	<div class="col-md-3 col-sm-4 col-xs-12">
      		<div class="panel text-center no-border bg-color-pink">
			<div class="panel-body">
		<a class="link" href="<?php echo base_url('administrator/payment/transaction?status=today'); ?>" ><?php echo $total_TodayTrips; ?></a>
    	</div>
    		<div class="panel-footer bg-foot-yellow">
            <?php echo 'Total Today Trips '; ?>
          </div>
         
         </div>
       </div>
       
    </div>

 		<div class="col-md-12 col-sm-12 col-xs-12" id="container" style="min-width:100%;height:400px;margin:0 auto;"></div>
 		</div> 
 
			<?php 
    $this->load->view('administrator/footer.php');
?>
<style>
.highcharts-menu-item
{/*

	font-size:3px;
	text-align:right;
	line-height:-2px;*/
/*line-height:3px;*/
	
}</style>
		<style>
		.highcharts-container , .highcharts-background
		{
			margin-left : 10px;
			fill: #ededed !important;
		}	
		.highcharts-credits
		{
			display: none;
		}	
		</style>
    
		   <style>
	
	
	text tspan:first-child + tspan {
			display: none;
		}
	
	
.highcharts-menu hr
{
	margin-top: 1px !important;
    margin-bottom: 3px !important;
    border: 0 ;
    border-top: 1px solid #ccc !important;
}
</style>	
		 <script src="https://code.highcharts.com/highcharts.js"></script>
		<script src="https://code.highcharts.com/modules/exporting.js"></script>
		
		
		
		
		<script> $(function () {
			$('#container').highcharts({
				chart: {
					pointFormat: '{series.name}: <b>{point.y}</b>',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %',
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false,
					type: 'pie'
				},
				title: {
										 text: '< style="color:black;">PIE CHART</style>'
				},
				tooltip: {
					pointFormat: '{series.name}: <b>{point.y}</b>'
				},
				plotOptions: {
					 series: {
                cursor: 'pointer',
                point: {
                    events: {
                        click: function () {
                            window.open(this.options.href,'_blank');
                        }
                    }
                }
            },
            	
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						
						dataLabels: {
							enabled: true,
							format: '<b>{point.name}</b>: {point.percentage:.1f} %',
							style: {
								color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'blue'
							}
						}
					}
				},
				series: [{
					name: 'Brands',
					colorByPoint: true,
					data: [ 
					{
						name: 'Total Riders',
						pointFormat: '{series.name}: <b>{point.y}</b>',
						format: '<b>{point.name}</b>',
						y: <?php echo $total_riders; ?>,						
						href :   "<?php echo base_url('administrator/members/Rider'); ?>"
					}, 
					{
						name: 'Today Riders',
						href :   "<?php echo base_url('administrator/members/rider?status=today'); ?>",
						y: <?php echo $today_Riders; ?>
					},  
					{
						name: 'Total Drivers',
						href :   "<?php echo base_url('administrator/members/Driver'); ?>",
						y:<?php echo $total_drivers; ?>
					}, 
					{
						name: 'Today Drivers',
						href :   "<?php echo base_url('administrator/members/Driver?status=today'); ?>",
						y:<?php echo $today_Driver; ?>
					}, 
					{
						name: 'Total Trips',
						href: "<?php echo base_url('administrator/payment/transaction');?>",
						y: <?php echo $total_trips; ?>
					}, 
					{
						name: 'Today Trips',
						href: "<?php echo base_url('administrator/payment/transaction?status=today');?>",
						y: <?php echo $total_trips; ?>
					}
					]
				}]
			});
		});
		
</script>
<style>

.col-md-12{
	margin:0px !important;
}

	.message{
		margin-top:10px;
	}
	hr{
		margin:0px !important ;
	}
	.highcharts-button-symbol
	{
		float:;
	}
	.link:focus, a:active {
		color:#000000 !important;
	}
</style>
