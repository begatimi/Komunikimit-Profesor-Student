<?php
	require('header.php');
?>
		<!-- page content -->	
		<div id="content">
			<div class="w3-content w3-display-container">
			  <img class="mySlides" src="images/slideshow/1.jpg" style="width:100%">
			  <img class="mySlides" src="images/slideshow/2.jpg" style="width:100%">
			  <img class="mySlides" src="images/slideshow/3.jpg" style="width:100%">
			  <img class="mySlides" src="images/slideshow/4.jpg" style="width:100%">

			  <button class="w3-button w3-black w3-display-left" onclick="plusDivs(-1)">&#10094;</button>
			  <button class="w3-button w3-black w3-display-right" onclick="plusDivs(1)">&#10095;</button>
			</div>

			<script>
				var slideIndex = 1;
				showDivs(slideIndex);

				function plusDivs(n) {
				  showDivs(slideIndex += n);
				}

				function showDivs(n) {
				  var i;
				  var x = document.getElementsByClassName("mySlides");
				  if (n > x.length) {slideIndex = 1}    
				  if (n < 1) {slideIndex = x.length}
				  for (i = 0; i < x.length; i++) {
					 x[i].style.display = "none";  
				  }
				  x[slideIndex-1].style.display = "block";  
				}
			</script>
			<div class="text-container">
			<h1 style="margin-top: 20px">About Professor Student Comunication Platform</h1>
			<p>Students can discuss anything about they classes with their instructors. Instructors and students can upload files that are visible to each other. Submitted reports are immediately available to the student, instructor. Additionally, They can discuss whithin a class without having to discuss with all the clases of the university.</p>
			<p>Student tutorials are generally more academically challenging and rigorous than standard lecture and test format courses, because during each session students are expected to orally communicate, defend, analyze, and critique the ideas of others as well as their own in conversations with the instructor and fellow-students. As a pedagogic model, the platform has great value because it creates learning and assessment opportunities. Teamwork is also easier with our platform.</p>
		</div>
<?php
	require('footer.php');
?>