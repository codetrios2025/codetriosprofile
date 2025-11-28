 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <div id="formMessage" style="margin-bottom:15px;"></div>
 <form id="contactForm">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required data-error="Please enter your name" maxlength="90">
          <div class="help-block with-errors"></div>
        </div>                                 
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <input type="email" placeholder="Your Email" id="email" class="form-control" name="email" required data-error="Please enter your email" maxlength="90">
          <div class="help-block with-errors"></div>
        </div> 
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" class="form-control" pattern="^[6-9]\d{9}$" maxlength="14" minlength="10" id="phoneNo" name="phoneNo" placeholder="Phone Number" required data-error="Please enter phone number">
          <div class="help-block with-errors"></div>
        </div>                                 
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" class="form-control" id="company" name="company" required placeholder="Company Name" required data-error="Please enter company name" maxlength="90">
          <div class="help-block with-errors"></div>
        </div>                                 
      </div>
      <div class="col-md-12">
        <div class="form-group"> 
          <textarea class="form-control" id="message" name="message" placeholder="Your Message" rows="8" data-error="Write your message" required maxlength="2000"></textarea>
          <div class="help-block with-errors"></div>
        </div>
        <div class="form-group">
          <div class="g-recaptcha" data-sitekey="6Ldc9-YrAAAAAPlEy99RO6N06uFh6LRqjJ76ggk4"></div>
        </div>
        <div class="submit-button text-center">
          <button class="btn btn-common" id="submit" type="submit">Send Message</button>
          <div id="msgSubmit" class="h3 text-center hidden"></div> 
          <div class="clearfix"></div> 
        </div>
      </div>
    </div>            
</form>
<script>
$(document).ready(function(){
  $("#contactForm").on("submit", function(e){
    e.preventDefault();
    $("#response").html("");

    $.ajax({
      url: "php/submit.php",
      method: "POST",
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function() {
        $("button").prop("disabled", true).text("Sending...");
      },
      success: function(response) {
        $("button").prop("disabled", false).text("Send Message");
        if(response.status === "success") {
          $('#formMessage').html('<span style="color:green;">' + response.message + '</span>');
          //showPopup(response.message);
          setTimeout(function() {
            window.location.href = "thankyou.html"; // <-- your thank you page
          }, 800);
          $('#contactForm')[0].reset();
          grecaptcha.reset(); // reset reCAPTCHA
          
        } else {
          $('#formMessage').html('<span style="color:red;">' + response.message + '</span>');
          //showPopup(response.message,true);
        }
      },
      error: function() {
        $("button").prop("disabled", false).text("Send Message");
        $('#formMessage').html('<span style="color:red;">An error occurred. Please try again.</span>');
      }
    });
  });
});
</script>
<!-- <div id="successPopup" 
     style="
        display:none;
        position:fixed;
        top:20px;
        right:20px;
        background:#28a745;
        color:#fff;
        padding:15px 20px;
        border-radius:8px;
        font-size:16px;
        box-shadow:0 0 10px rgba(0,0,0,0.2);
        z-index:9999;
     ">
</div>
<script>
    function showPopup(message, isError = false) {
      let popup = document.getElementById("successPopup");

      popup.innerHTML = message;
      popup.style.background = isError ? "#dc3545" : "#28a745"; // red or green
      popup.style.display = "block";

      setTimeout(() => {
          popup.style.display = "none";
      }, 50000); // hide after 5 seconds000); // hide after 5 seconds
    }
</script> -->