 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <div id="formMessage" style="margin-bottom:15px;"></div>
 <form id="contactForm">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" class="form-control" id="name" name="name" placeholder="Your Name" required data-error="Please enter your name">
          <div class="help-block with-errors"></div>
        </div>                                 
      </div>
      <div class="col-md-12">
        <div class="form-group">
          <input type="text" placeholder="Your Email" id="email" class="form-control" name="email" required data-error="Please enter your email">
          <div class="help-block with-errors"></div>
        </div> 
      </div>
      <div class="col-md-12">
        <div class="form-group"> 
          <textarea class="form-control" id="message" name="message" placeholder="Your Message" rows="8" data-error="Write your message" required></textarea>
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
          $('#contactForm')[0].reset();
          grecaptcha.reset(); // reset reCAPTCHA
          
        } else {
          $('#formMessage').html('<span style="color:red;">' + response.message + '</span>');
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