<!--hero section start-->

<section class="bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h2 mb-0">Contact Us</h1>
            </div>
            <div class="col-md-6 mt-3 mt-md-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-md-end bg-transparent p-0 m-0">
                        <li class="breadcrumb-item"><a class="text-dark" href="/"><i class="las la-home mr-1"></i>Home</a>
                        </li>
                        <li class="breadcrumb-item active text-primary" aria-current="page">Contact Us</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- / .row -->
    </div>
    <!-- / .container -->
</section>

<!--hero section end-->

<!--body content start-->

<div class="page-content">

    <section>
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-8">
                    <div class="mb-5">
                        <h6 class="text-primary mb-1">— Contact US</h6>
                        <h2 class="mb-0">We’d love to hear from you.</h2>
                    </div>
                    <form class="row" method="post" action="{{ route('contact.web.contact.send-mail') }}">
                        @csrf
                        <div class="messages"></div>
                        <div class="form-group col-md-6">
                            <label>Full Name<span class="text-danger">*</span></label>
                            <input id="form_name" type="text" name="name" class="form-control" placeholder="Your Name" required="required" data-error="Name is required.">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone Number <span class="text-danger">*</span></label>
                            <input id="form_phone" type="text" name="phone" class="form-control" placeholder="Phone" required="required" data-error="Phone is required">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Email Address <span class="text-danger">*</span></label>
                            <input id="form_email" type="email" name="email" class="form-control" placeholder="Email" required="required" data-error="Valid email is required.">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Message <span class="text-danger">*</span></label>
                            <textarea id="form_message" name="content" class="form-control" placeholder="Message" rows="4" required="required" data-error="Please,leave us a message."></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <button type="submit" class="btn btn-primary btn-animated"><span>Send Messages</span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-lg-4 mt-6 mt-lg-0">
                    <div class="shadow-sm rounded p-5">
                        <div class="mb-5">
                            <h6 class="text-primary mb-1">— Contact Info</h6>
                            <h4 class="mb-0">We Are here To help You</h4>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="mr-2"> <i class="las la-map ic-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Store address</h6>
                                <p class="mb-0 text-muted">423B, Road Wordwide Country, USA</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="mr-2"> <i class="las la-envelope ic-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Email Us</h6>
                                <a class="text-muted" href="mailto:themeht23@gmail.com"> themeht23@gmail.com</a>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="mr-2"> <i class="las la-mobile ic-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Phone Number</h6>
                                <a class="text-muted" href="tel:+912345678900">+91-234-567-8900</a>
                            </div>
                        </div>
                        <div class="d-flex mb-5">
                            <div class="mr-2"> <i class="las la-clock ic-2x text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1 text-dark">Working Hours</h6>
                                <span class="text-muted">Mon - Fri: 10AM - 7PM</span>
                            </div>
                        </div>
                        <ul class="list-inline">
                            <li class="list-inline-item"><a class="bg-white shadow-sm rounded p-2" href="#"><i class="la la-facebook"></i></a>
                            </li>
                            <li class="list-inline-item"><a class="bg-white shadow-sm rounded p-2" href="#"><i class="la la-dribbble"></i></a>
                            </li>
                            <li class="list-inline-item"><a class="bg-white shadow-sm rounded p-2" href="#"><i class="la la-instagram"></i></a>
                            </li>
                            <li class="list-inline-item"><a class="bg-white shadow-sm rounded p-2" href="#"><i class="la la-twitter"></i></a>
                            </li>
                            <li class="list-inline-item"><a class="bg-white shadow-sm rounded p-2" href="#"><i class="la la-linkedin"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </section>

<!--body content end-->

    <section class="pt-0">
        <div class="container">
            <hr class="mt-0 mb-10">
            <div class="row justify-content-center text-center mb-5">
                <div class="col-lg-8">
                    <div>
                        <h6 class="text-primary mb-1">— Easy to Find</h6>
                        <h2 class="mb-0">Our Store Location</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="map" style="height: 500px;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.840108181602!2d144.95373631539215!3d-37.8172139797516!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4c2b349649%3A0xb6899234e561db11!2sEnvato!5e0!3m2!1sen!2sin!4v1497005461921" allowfullscreen="" class="w-100 h-100 border-0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--instagram start-->

{!! SliderRender::render('follow-instagram', 'slider.follow-instagram-slider') !!}

<!--instagram end-->

</div>

<!--body content end-->

@section('mapscript')
    <script src="{{ theme_url('plugins/js/ajax-contact.js') }}"></script>
    <!--====== Google Map js ======-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQ5y0EF8dE6qwc03FcbXHJfXr4vEa7z54"></script>
    <script src="{{ theme_url('js/map-script.js') }}"></script>
@endsection
