<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> منصة بيان للتدريب | الرئيسية</title>
    <link rel=icon href="assets/frontend/img/favicon.png" sizes="20x20" type="image/png">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/flaticon.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/nice-select.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/magnific.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/spacing.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/frontend/css/style.css') }}" />
</head>

<body class='sc5'>

    <!-- preloader area start -->
    <div class="preloader" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    <!-- preloader area end -->

    <!-- search popup start-->
    <div class="td-search-popup" id="td-search-popup">
        <form action="#" class="search-form">
            <div class="form-group">
                <input type="text" class="form-control" style="direction: rtl;" placeholder="البحث عن...">
            </div>
            <button type="submit" class="submit-btn"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <!-- search popup end-->
    <div class="body-overlay" id="body-overlay"></div>

    <!-- navbar start -->
        <nav class="navbar navbar--two navbar-area navbar-expand-lg">
            <div class="container nav-container navbar-bg">
                <div class="responsive-mobile-menu">
                    <button class="menu toggle-btn d-block d-lg-none" data-target="#Iitechie_main_menu"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon-left"></span>
                        <span class="icon-right"></span>
                    </button>
                </div>
                <div class="logo">
                    <a href="#"><img src="../assets/frontend/img/logos/logo.png" alt="img"></a>
                </div>
                <div class="nav-right-part nav-right-part-mobile">
                    <a class="search-bar-btn" href="#">
                        <i class="flaticon-magnifying-glass"></i>
                    </a>
                </div>
                <div class="collapse navbar-collapse" id="Iitechie_main_menu">
                    <ul class="navbar-nav menu-open text-lg-end">
                        <li class="menu-item-has-children">
                            <a href="#">الرئيسية</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">من نحن</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">كيف نعمل؟</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الدورات التدريبية</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">الأخبار</a>
                        </li>
                        <li class="menu-item-has-children">
                            <a href="#">تواصل معنا</a>
                        </li>
                    </ul>
                </div>
                <div class="nav-right-part nav-right-part-desktop">
                    <a class="search-bar-btn" href="#">
                        <i class="flaticon-magnifying-glass"></i>
                    </a>
                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="flaticon-user-1"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" style="text-align: right;" href="{{ url('/login') }}">دخول الطلاب</a>
                            <a class="dropdown-item" style="text-align: right;" href="{{ url('/judge/login') }}">دخول القضاة</a>
                        </div>
                    </div>

                    <a class="btn btn--style-two" style="font-weight: 400 !important;" href="{{ route('register.student') }}">متدرب جديد</a>
                </div>
            </div>
        </nav>
    <!-- navbar end -->

    <!-- Hero start -->
        <div class="hero-area-two bgs-cover overlay py-250" style="position: relative; overflow: hidden;">
            <video autoplay muted loop playsinline class="background-video">
                <source src="assets/frontend/img/hero/background.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="overlay-layer"></div>
            <div class="container" style="position: relative; z-index: 1;">
                <div class="hero-content mt-110 rmt-0 mb-65 text-center text-white rel z-1">
                    <h1 style="font-size: 85px;">منصة بيان للتدريب طريقك للنجاح</h1>
                    <p>المنصة تقدم لطلاب القانون دورات تدريبية ومحاكاة حقيقية للقضايا بجميع انواعها ومستوياتها والتحكيم بواسطة قضاة حقيقيون</p>
                    <div class="hero-btns pt-15 rpt-0">
                        <a class="btn" href="#">شاهد طريقة العمل</a>
                        <a class="btn mt-30" href="#">اشترك معنا الآن</a>
                    </div>
                    <img class="hero-shape-two top_image_bounce" src="assets/frontend/img/shapes/three-round-green.png" alt="Shape">
                </div>
            </div>
        </div>
    <!-- Hero end -->


    <!-- Features area start -->
        <div class="features-area-two rel z-2">
            <div class="container">
                <div class="row no-gutter justify-content-center">
                    <div class="col-xl-3 col-md-6">
                        <div class="testimonial-item-three" style="margin-right: 5px;">
                            <div class="author"><img src="assets/frontend/img/testimonials/author1.jpg" alt="Author"></div>
                            <h4 class="name">استشارات</h4>
                            <span class="designation">وصف استشارات</span>
                            <div class="hero-btns pt-15 rpt-0">
                                <a class="btn" href="#">شاهد الدورات</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="testimonial-item-three" style="margin-left: 5px; margin-right: 5px;">
                        <div class="author"><img src="assets/frontend/img/testimonials/author1.jpg" alt="Author"></div>
                        <h4 class="name">محامي نقض</h4>
                            <span class="designation">وصف محامي نقض</span>
                            <div class="hero-btns pt-15 rpt-0">
                                <a class="btn" href="#">شاهد الدورات</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="testimonial-item-three" style="margin-left: 5px; margin-right: 5px;">
                        <div class="author"><img src="assets/frontend/img/testimonials/author1.jpg" alt="Author"></div>
                        <h4 class="name">محامي استئناف</h4>
                            <span class="designation">وصف محامي استئناف </span>
                            <div class="hero-btns pt-15 rpt-0">
                                <a class="btn" href="#">شاهد الدورات</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-6">
                        <div class="testimonial-item-three" style="margin-left: 5px;">
                        <div class="author"><img src="assets/frontend/img/testimonials/author1.jpg" alt="Author"></div>
                        <h4 class="name">محامي درجة أولى</h4>
                            <span class="designation"> وصف محامي درجة أولى</span>
                            <div class="hero-btns pt-15 rpt-0">
                                <a class="btn" href="#">شاهد الدورات</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Features area end -->


    <!-- Our Causes area start -->
    <div class="our-causes-three bgc-black pt-120 pb-90 rel z-1" style="margin-top: 70px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-7 col-md-9">
                    <div class="section-title text-center text-white mb-55">
                        <h2>أحدث دوراتنا التدريبية</h2>
                        <p>تقدم منصة بيان للتدريب مجموعة قيمة من الدورات التدريبية لطلاب القانون, باستخدام أحدث التقنيات والتكنولوجيا الحديثة</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-3 col-md-6">
                    <div class="cause-three-item">
                        <div class="image">
                            <img src="assets/frontend/img/causes/cause-three1.jpg" alt="Cause">
                            <div class="skillbar" data-percent="80">
                                <span class="skill-bar-percent" style="left: 80%;"></span>
                                <div class="skillbar-bar"></div>
                            </div>
                        </div>
                        <div class="content">
                            <h4 style="text-align: right;"><a href="#">دورة قانون الشركات </a></h4>
                            <p style="text-align: justify;">تغطي هذه الدورة الجوانب القانونية المتعلقة بتأسيس وإدارة الشركات والأعمال التجارية. يتعلم الطلاب القوانين المتعلقة بالشركات، حقوق وواجبات المساهمين، العقود التجارية، وحماية الملكية الفكرية.</p>
                            <div class="cause-price">
                                <span><i class="flaticon-line-chart"></i> التقييم: 98%</span>
                                <span><i class="flaticon-target"></i>التقدم : 80%</span>
                            </div>
                            <div class="cause-btn" style="text-align: center;">
                                <a class="btn" href="#">اشترك الآن</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="cause-three-item">
                        <div class="image">
                            <img src="assets/frontend/img/causes/cause-three2.jpg" alt="Cause">
                            <div class="skillbar" data-percent="50">
                                <span class="skill-bar-percent" style="left: 50%;"></span>
                                <div class="skillbar-bar"></div>
                            </div>
                        </div>
                        <div class="content">
                            <h4 style="text-align: right;"><a href="#"> الوساطة القانونية</a></h4>
                            <p style="text-align: justify;">تهدف هذه الدورة إلى تزويد الطلاب بالمهارات الأساسية في التفاوض وحل النزاعات بطريقة ودية. يتعلم الطلاب استراتيجيات التفاوض الفعالة وتقنيات الوساطة لتعزيز الأطراف في النزاعات القانونية.</p>
                            <div class="cause-price">
                                <span><i class="flaticon-line-chart"></i> التقييم: 94%</span>
                                <span><i class="flaticon-target"></i>التقدم : 50%</span>
                            </div>
                            <div class="cause-btn" style="text-align: center;">
                                <a class="btn" href="#">اشترك الآن</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="cause-three-item">
                        <div class="image">
                            <img src="assets/frontend/img/causes/cause-three3.jpg" alt="Cause">
                            <div class="skillbar" data-percent="70">
                                <span class="skill-bar-percent" style="left: 70%;"></span>
                                <div class="skillbar-bar"></div>
                            </div>
                        </div>
                        <div class="content">
                            <h4 style="text-align: right;"><a href="#">القانون الجنائي </a></h4>
                            <p style="text-align: justify;">تركز هذه الدورة على تعميق فهم الطلاب لمبادئ وأسس القانون الجنائي، بما في ذلك الجرائم والعقوبات، والإجراءات الجنائية. يتم دراسة حالات واقعية وتحليل القرارات القضائية للطلاب في المجال الجنائي.</p>
                            <div class="cause-price">
                                <span><i class="flaticon-line-chart"></i> التقييم: 90%</span>
                                <span><i class="flaticon-target"></i>التقدم : 70%</span>
                            </div>
                            <div class="cause-btn" style="text-align: center;">
                                <a class="btn" href="#">اشترك الآن</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6">
                    <div class="cause-three-item">
                        <div class="image">
                        <img src="assets/frontend/img/causes/cause-three2.jpg" alt="Cause">
                        <div class="skillbar" data-percent="60">
                                <span class="skill-bar-percent" style="left: 60%;"></span>
                                <div class="skillbar-bar"></div>
                            </div>
                        </div>
                        <div class="content">
                            <h4 style="text-align: right;"><a href="#">قانون العمل والعمال</a></h4>
                            <p style="text-align: justify;">تهدف هذه الدورة إلى تزويد الطلاب بالمعرفة الشاملة حول قوانين العمل والعمال وحقوق العمال وأرباب العمل. يتعلم الطلاب اللوائح والقوانين التي تنظم علاقات العمل، وكيفية حل النزاعات العمالية بفعالية.</p>
                            <div class="cause-price">
                                <span><i class="flaticon-line-chart"></i> التقييم: 85%</span>
                                <span><i class="flaticon-target"></i>التقدم : 60%</span>
                            </div>
                            <div class="cause-btn" style="text-align: center;">
                                <a class="btn" href="#">اشترك الآن</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Our Causes area end -->

    <!-- FAQ area start -->
        <div class="faq-area-three py-100">
            <div class="container">
                <div class="row gap-60 align-items-center">
                    <div class="col-lg-6">
                        <div class="faq-three-left-part mb-20 rel">
                            <img src="assets/frontend/img/about/faq-left.png" alt="Man">
                            <div class="experiences-years" style="direction: rtl;">
                                <span class="experiences-years__number">86</span>
                                <span class="experiences-years__text">قضايا قيد التدريب</span>
                            </div>
                            <div class="counter-item counter-text-wrap">
                                <div class="counter-item__content">
                                    <span class="count-text" data-speed="1000" data-stop="1800">0</span>
                                    <h5 class="counter-title">طلاب مسجلين</h5>
                                </div>
                            </div>
                            <div class="project-complete" style="direction: rtl;">
                                <div class="project-complete__icon">
                                    <i class="flaticon-charity"></i>
                                </div>
                                <div class="project-complete__content">
                                    <h5>بكل حب نقدم لكم حدماتنا</h5>
                                    <span>منصة بيان للتدريب</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="faq-content-part mt-20 rmt-65" style="direction: rtl;">
                            <div class="section-title mb-45">
                                <h2 style="text-align: right;">هل تحتاج مساعدة؟</h2>
                                <p style="text-align: right;">قبل التواصل معنا الرجاء قراءة الاسئلة الشائعة التي قام زملائك بالاستفسار عنها في الأوقات السابقة.</p>
                            </div>
                            <div class="faq-accordion-two" id="faqAccordion">
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingThree">
                                        <button class="collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            ما هي منصة بيان وكيف يمكنني الاستفادة منها كطالب محاماة؟
                                        </button>
                                    </h5>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="text-align: right;">
                                        منصة تعليمية تفاعلية تهدف إلى تدريب طلاب المحاماة على الإجراءات القانونية والمحاكمات من خلال محاكاة واقعية لجلسات المحكمة. توفر المنصة فرصة للتعلم العملي والتفاعل مع الحالات القانونية بشكل مباشر، مما يساعد الطلاب على تطوير مهاراتهم في تقديم القضايا والدفاع عنها، وكذلك في فهم الإجراءات القانونية المختلفة.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingOne">
                                        <button type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                            aria-expanded="true" aria-controls="collapseOne">
                                            كيف يمكنني التسجيل في  المنصة والبدء في استخدامها؟
                                        </button>
                                    </h5>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="text-align: right;">
                                        انقر على زر "تسجيل" في الصفحة الرئيسية.
                                        أدخل معلوماتك الشخصية والأكاديمية المطلوبة في نموذج التسجيل.
                                        بعد إكمال التسجيل، ستتلقى بريدًا إلكترونيًا يحتوي على رابط التفعيل.
                                        انقر على رابط التفعيل لتفعيل حسابك والبدء في استخدام المنصة.
                                        بمجرد تسجيل الدخول، يمكنك استكشاف الدورات المتاحة والحالات القانونية التي يمكنك التدريب عليها.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingTwo">
                                        <button class="collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            هل يتطلب استخدام المنصة أي متطلبات تقنية خاصة؟
                                        </button>
                                    </h5>
                                    <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                        data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="text-align: right;">
                                        لا يتطلب استخدام المحكمة الافتراضية أي متطلبات تقنية خاصة. كل ما تحتاجه هو:

                                        جهاز كمبيوتر أو جهاز محمول (مثل هاتف ذكي أو تابلت) مزود بمتصفح ويب حديث.
                                        اتصال بالإنترنت مستقر.
                                        يفضل استخدام سماعات وميكروفون جيدين لتجربة تفاعلية أفضل أثناء جلسات المحاكمة الافتراضية.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingThree">
                                        <button class="collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            ما هي منصة بيان وكيف يمكنني الاستفادة منها كطالب محاماة؟
                                        </button>
                                    </h5>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="text-align: right;">
                                        منصة تعليمية تفاعلية تهدف إلى تدريب طلاب المحاماة على الإجراءات القانونية والمحاكمات من خلال محاكاة واقعية لجلسات المحكمة. توفر المنصة فرصة للتعلم العملي والتفاعل مع الحالات القانونية بشكل مباشر، مما يساعد الطلاب على تطوير مهاراتهم في تقديم القضايا والدفاع عنها، وكذلك في فهم الإجراءات القانونية المختلفة.
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item">
                                    <h5 class="accordion-header" id="headingThree">
                                        <button class="collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            ما هي منصة بيان وكيف يمكنني الاستفادة منها كطالب محاماة؟
                                        </button>
                                    </h5>
                                    <div id="collapseThree" class="accordion-collapse collapse"
                                        aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                                        <div class="accordion-body" style="text-align: right;">
                                        منصة تعليمية تفاعلية تهدف إلى تدريب طلاب المحاماة على الإجراءات القانونية والمحاكمات من خلال محاكاة واقعية لجلسات المحكمة. توفر المنصة فرصة للتعلم العملي والتفاعل مع الحالات القانونية بشكل مباشر، مما يساعد الطلاب على تطوير مهاراتهم في تقديم القضايا والدفاع عنها، وكذلك في فهم الإجراءات القانونية المختلفة.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
         </div>
    <!-- FAQ area end -->

    <!-- Blog area start -->
        <div class="blog-area-three pb-90 rel z-1" dir="rtl" style="margin-top: 70px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-8 col-md-10">
                        <div class="section-title text-center mb-60">
                            <h2>تابع آخر أخبارنا في المدونة</h2>
                            <p>آخر الأخبار الخاصة بالقانون ومهنة المحاماة والقضاء تجدها في المدونة الخاصة بمنصة بيان للتدريب</p>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-item--three">
                            <div class="blog-item__img">
                                <img src="assets/frontend/img/blog/blog1.jpg" alt="Blog">
                                <div class="post-date">
                                    <b>13</b>
                                    <span>ديسمبر</span>
                                </div>
                            </div>
                            <div class="blog-item__content">
                                <h6 style="text-align: right;"><a href="#">أحدث التعديلات على قانون الإجراءات الجنائية</a></h6>
                                <p style="text-align: right;">تم إصدار تعديلات جديدة على قانون الإجراءات الجنائية لتعزيز العدالة وتسريع البت في القضايا.</p>
                                <a href="#" class="read-more-two">اقرأ المزيد</a>
                            </div>
                            <ul class="blog-item__meta">
                                <li><i class="flaticon-user"></i> <a href="#">منصة بيان</a></li>
                                <li class="line"></li>
                                <li><i class="flaticon-bubble-chat"></i> <a href="#">05 تعليقات</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-item--three">
                            <div class="blog-item__img">
                                <img src="assets/frontend/img/blog/blog4.jpg" alt="Blog">
                                <div class="post-date">
                                    <b>21</b>
                                    <span>ديسمبر</span>
                                </div>
                            </div>
                            <div class="blog-item__content">
                                <h6 style="text-align: right;"><a href="#">دور المحامي في قضايا التحكيم التجاري</a></h6>
                                <p style="text-align: right;">تناقش هذه المقالة أهمية دور المحامي في قضايا التحكيم التجاري وكيفية تمثيل العملاء بفعالية.</p>
                                <a href="#" class="read-more-two">اقرأ المزيد</a>
                            </div>
                            <ul class="blog-item__meta">
                                <li><i class="flaticon-user"></i> <a href="#">منصة بيان</a></li>
                                <li class="line"></li>
                                <li><i class="flaticon-bubble-chat"></i> <a href="#">05 تعليقات</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-6">
                        <div class="blog-item--three">
                            <div class="blog-item__img">
                                <img src="assets/frontend/img/blog/blog5.jpg" alt="Blog">
                                <div class="post-date">
                                    <b>13</b>
                                    <span>ديسمبر</span>
                                </div>
                            </div>
                            <div class="blog-item__content">
                                <h6 style="text-align: right;"><a href="#">التحديات القانونية في قضايا الجرائم الإلكترونية</a></h6>
                                <p style="text-align: right;">تستعرض هذه المقالة التحديات القانونية التي يواجهها المحامون في التعامل مع قضايا الجرائم الإلكترونية.</p>
                                <a href="#" class="read-more-two">اقرأ المزيد</a>
                            </div>
                            <ul class="blog-item__meta">
                                <li><i class="flaticon-user"></i> <a href="#">منصة بيان</a></li>
                                <li class="line"></li>
                                <li><i class="flaticon-bubble-chat"></i> <a href="#">05 تعليقات</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Blog area end -->

    <!-- Client Logo area start -->
        <div class="client-logo-area py-75"
                style="background-image: url(assets/frontend/img/client-logo/client-logo-section-bg.jpg);">
                <div class="container container-1370">
                    <h2 style="text-align: center;">شركاء النجاح</h2>
                    <p style="text-align: center; margin-bottom: 50px;">إن تعاوننا المستمر مع شركائنا يعزز من قدرتنا على تقديم تجربة مستخدم متميزة، تسهم في تسهيل الوصول إلى العدالة وتسريع الإجراءات القانونية.</p>
                    <div class="client-logo-wrap">
                        <div class="client-logo-item">
                        <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                        <div class="client-logo-item">
                        <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                        <div class="client-logo-item">
                            <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                        <div class="client-logo-item">
                        <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                        <div class="client-logo-item">
                        <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                        <div class="client-logo-item">
                        <a href="#"><img src="assets/frontend/img/client-logo/najez.png" alt="Client Logo"></a>
                        </div>
                    </div>
                </div>
        </div>
    <!-- Client Logo area end -->

    <!-- footer area start -->
        <footer class="footer-area footer-area--two text-white pt-120">
            <div class="container">
                <div class="row justify-content-between" style="direction: rtl;">
                    <div class="col-xl-3 col-sm-6">
                        <div class="widget widget_about">
                            <div class="logo_footer mb-25">
                                <a href="#">
                                    <img src="assets/frontend/img/logos/logo-white.png" alt="Logo">
                                </a>
                            </div>
                            <p>نبذة مختصرة عن المنصة ونشاطها وما تقدمه لعملائها في جميع الانشطة من دورات تدريبية وشهادات وانشطة</p>
                            
            <div class="footer-social-2">
                <a href="https://tptc.com.sa" target="_blank" style="display: flex; align-items: center;">
                <img src="https://tptc.com.sa/tptc_logo.png" alt="Logo" style="width: 60px !important; height: 60px; margin-left: 10px;">
                <p style="margin: 0;">تصميم وتطوير باقة التقنية</p>
                </a>
            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-3 col-6">
                        <div class="widget widget_nav_menu">
                            <h5 class="widget-title">عن المنصة</h5>
                            <ul>
                                <li><a href="#">تاريخنا</a></li>
                                <li><a href="#">دوراتنا</a></li>
                                <li><a href="#">انجازاتنا</a></li>
                                <li><a href="#">فريقنا</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-sm-3 col-6">
                        <div class="widget widget_nav_menu">
                            <h5 class="widget-title">روابط هامة</h5>
                            <ul>
                                <li><a href="#">القضاة</a></li>
                                <li><a href="#">المحاميين</a></li>
                                <li><a href="#">انضم الينا</a></li>
                                <li><a href="#">تواصل معنا</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-5">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="widget widget_gallery">
                                    <h4 class="widget-title">معرض الصور</h4>
                                    <div class="gallery-photos">
                                        <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                                <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                                <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                                <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                                <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                                <a href="assets/frontend/img/footer/gallery-two1.jpg"><img
                                                src="assets/frontend/img/footer/gallery-two1.jpg" alt="Gallery"></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="widget widget_subscribe">
                                    <h4 class="widget-title">النشرة البريدية</h4>
                                    <p>اشترك معنا ليصلك جديدنا من دورات تدريبية</p>
                                    <form action="#">
                                        <input type="email" placeholder="البريد الالكتروني" required>
                                        <button type="submit"><i class="flaticon-left-arrow"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom mt-70">
                <div class="container">
                    <div class="footer-bottom__inner">
                        <div class="donate-by">
                            <img src="assets/frontend/img/footer/donate-by.png" alt="Donate By">
                        </div>
                        <div class="copyright">
                            <p>جميع الحقوق محفوظة 2024 لمنصة بيان للتدريب</p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    <!-- footer area end -->

    <!-- back to top area start -->
    <div class="back-to-top">
        <span class="back-top"><i class="fa fa-angle-up"></i></span>
    </div>
    <!-- back to top area end -->


<script src="{{ asset('assets/frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/nice-select.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/circle-progress.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/skill.bars.jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/magnific.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/appear.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/isotope.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/imageload.min.js') }}"></script>
<script src="{{ asset('assets/frontend/js/slick.min.js') }}"></script>


<script src="{{ asset('assets/frontend/js/main.js') }}"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>