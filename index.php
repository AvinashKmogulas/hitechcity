<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <form name="bookinghotelform" id="bookinghotelform" class="booking-form" target="_blank" method="post">
        <div class="row g-0">
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Name</label>
                    <div class="form-field">
                        <div class="icon"><span class="far fa-solid fa-user"></span></div> <input type="text" name="Name" class="form-control" id="be-name" placeholder="Enter Name">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Phone Number</label>
                    <div class="form-field">
                        <div class="icon"><span class="fas fa-phone-alt"></span></div> <input type="number" name="Phone No" class="form-control" id="be-number" placeholder="Enter Number">
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Check-In</label>
                    <div class="form-field">
                        <div class="icon"><span class="fa fa-calendar"></span></div> <input type="text" name="eZ_chkin" class="form-control be-checkin hasDatepicker" id="datepicker" placeholder="Check In" readonly="readonly"><button type="button" class="ui-datepicker-trigger"></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Check-Out</label>
                    <div class="form-field">
                        <div class="icon"><span class="fa fa-calendar"></span></div> <input type="text" name="eZ_chkout" class="form-control be-checkout hasDatepicker" id="datepicker2" placeholder="Check Out" readonly="readonly"><button type="button" class="ui-datepicker-trigger"></button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Rooms</label>
                    <div class="form-field">
                        <div class="select-wrap"> <select name="Room" id="be-rooms" class="form-control">
                                <option value="">Room</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Guests</label>
                    <div class="form-field">
                        <div class="select-wrap"> <select name="Guest" id="be-adults" class="form-control">
                                <option value="">Adult</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                            </select></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg form-wrap">
                <div class="form-group"> <label for="#">Children</label>
                    <div class="form-field">
                        <div class="select-wrap"> <select name="Children" id="be-childs" class="form-control">
                                <option value="">Children</option>
                                <option value="0">0</option>
                                <option value="1">1</option>
                            </select></div>
                    </div>
                </div>
            </div> <input type="hidden" name="Bucket" id="bucket" value="">
            <input type="hidden" id="c_date" name="Query-date" value="">
            <div class="col-md-6 col-lg text-center"><button type="submit" class="btn">Book Now </button></div>
        </div>
    </form>
    <script>
        $("document").ready(function() {
            let scriptUrl =
                "https://script.google.com/macros/s/AKfycbxndQqts44fnZvpVC75bTUl_Lg4XpTyi8EZS_NQsH1FL-gghVfjz_Cq8jJKIYhCj2BqqQ/exec";
            let bookingForm = document.forms["bookinghotelform"];
            bookingForm.addEventListener("submit", function(event) {
                event.preventDefault();
                let name = $("#be-name").val();
                let phone = $("#be-number").val();
                let checkin = $("#datepicker").val();
                let checkout = $("#datepicker2").val();
                let rooms = $("#be-rooms").val();
                let adults = $("#be-adults").val();
                let children = $("#be-childs").val();
                let RegEx = /^[a-zA-Z][a-zA-Z ]+$/;
                let RegPhNo = /^[0-9,()-]{1,50}$/;

                if (name === "" || phone === "" || rooms === "" || adults === "" || children === "") {
                    alert("Please fill all required fields");
                    return;
                }

                if (!RegEx.test(name)) {
                    alert("Invalid Name");
                    return;
                }
                if (!RegPhNo.test(phone)) {
                    alert("Invalid Phone Number");
                    return;
                }

                var fullDate = new Date();
                twoDigitMonth =
                    fullDate.getMonth().length + 1 === 1 ?
                    fullDate.getMonth() + 1 :
                    "0" + (fullDate.getMonth() + 1);
                var currentDate =
                    fullDate.getDate() + "-" + twoDigitMonth + "-" + fullDate.getFullYear();
                $("#c_date").attr("value", currentDate);

                sendBookingMail(name, phone, checkin, checkout, rooms, adults, children);

                fetch(scriptUrl, {
                    method: "POST",
                    body: new FormData(bookingForm)
                });
            });

            function sendBookingMail(name, phone, checkin, checkout, rooms, adults, children) {
                $.ajax({
                    url: "mailSend.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        name: name,
                        phone: phone,
                        checkin: checkin,
                        checkout: checkout,
                        rooms: rooms,
                        adults: adults,
                        children: children,
                        flag: "bookingForm",
                    },
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Form submitted successfully!");
                            $("#bookinghotelform")[0].reset();
                        } else {
                            alert("Mailer Error: " + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", error);
                        alert(error);
                    },
                });
            }
        });
    </script>
</body>

</html>