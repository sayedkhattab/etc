@include('layouts.newnavbar')

	<!-- Banner Area Start -->
	<div class="banner__one swiper banner-one-slider">
		<div class="swiper-wrapper">
			<div class="banner__one-image swiper-slide" data-background="assets/img/banner/banner-1.jpg">
				
					<div class="row">
						<div class="col-xl-12">
							<div class="banner__one-content"> 
								<span data-animation="fadeInUp" data-delay=".4s">مرحبا بك في المحكمة الإفتراضية</span>
								<h1 data-animation="fadeInUp" data-delay=".7s"> منصة تدريب  المحاميين</h1>
								<div class="banner__one-content-button" data-animation="fadeInUp" data-delay="1s">
									<div class="banner__one-content-button-item">
										<a class="btn-one" href="#"><i class="far fa-chevron-double-left" style="margin-right: 25px !important"></i>انضم الينا الأن</a>
									</div> 
									<div class="banner__one-content-video-icon">
										<a class="video-popup" href="#"><i class="fas fa-play"></i></a>										
									</div> 
								</div>
								<div class="banner-four-pagination"></div>
							</div>
						</div>
					</div>
				
			</div>
			<div class="banner__one-image swiper-slide" data-background="assets/img/banner/banner-22.jpg">				
				
					<div class="row">
						<div class="col-xl-12">
							<div class="banner__one-content"> 
								<span data-animation="fadeInUp" data-delay=".4s">انضم الينا وطور مهاراتك</span>
								<h1 data-animation="fadeInUp" data-delay=".7s">تعلم تدرب طور مهاراتك</h1>
								<div class="banner__one-content-button" data-animation="fadeInUp" data-delay="1s">
									<div class="banner__one-content-button-item">
										<a class="btn-one" href="#"><i class="far fa-chevron-double-left" style="margin-right: 25px !important"></i>انضم الينا الأن</a>
									</div> 
									<div class="banner__one-content-video-icon">
										<a class="video-popup" href="#"><i class="fas fa-play"></i></a>										
									</div> 
								</div>
								<div class="banner-four-pagination"></div>
							</div>
						</div>
					</div>
				
			</div>
		</div>
	</div>
	<!-- Banner Area End -->

<main class="content" style="margin-bottom: 50px; margin-top: 50px; text-align: center;">
	<div class="w-full max-w-2xl px-6 lg:max-w-7xl">
		<h1 class="text-4xl font-bold mb-4">We are currently working on<br> something awesome!</h1>
		<p class="text-xl mb-8" style="margin-bottom: 50px;">Our website is under construction. We'll be here soon with our new awesome site.</p>
		<div id="countdown" class="text-4xl font-semibold mb-8" style="font-size: 60px;"></div>
	</div>
</main>

<script>
	function countdownTimer() {
		const countdownElement = document.getElementById('countdown');
		const endTime = new Date(Date.now() + 70 * 24 * 60 * 60 * 1000); // 70 days from now

		function updateCountdown() {
			const now = new Date();
			const timeRemaining = endTime - now;

			if (timeRemaining <= 0) {
				countdownElement.innerHTML = "The wait is over!";
				return;
			}

			const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
			const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
			const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
			const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

			countdownElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
		}

		updateCountdown();
		setInterval(updateCountdown, 1000);
	}

	countdownTimer();
</script>
@include('layouts.newfooter')