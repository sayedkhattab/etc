@include('admin.partials.header')
<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg overflow-x-hidden">
  @include('admin.partials.sidebar')
  <div class="main-content">
    @include('admin.partials.navbar')
    <div class="container-fluid py-4">
      <!-- محتوى الصفحة الرئيسي -->
      <div class="row">
        <div class="col-lg-12 mb-lg-0 mb-4">
          <div class="card">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between align-items-center">
                <h6>جميع القضايا</h6>
                <div class="input-group w-50">
                  <input type="text" class="form-control" placeholder="البحث برقم القضية">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button">بحث</button>
                  </div>
                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">رقم القضية</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">تاريخ القضية</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">نوع القضية</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الصفة</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المدعي</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">المدعي عليه</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الحالة</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">الإجراء</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>4571414284</td>
                      <td>1445/12/03</td>
                      <td>أجرة أعمال</td>
                      <td>ممثل</td>
                      <td>شركة فراند الأعمال للتشغيل</td>
                      <td>شركة أجال العربية المحدودة</td>
                      <td>قيد النظر</td>
                      <td><a href="#" class="btn btn-info btn-sm">عرض</a></td>
                    </tr>
                    <tr>
                      <td>4571307760</td>
                      <td>1445/11/05</td>
                      <td>إنهاء العلاقة العمالية من أ...</td>
                      <td>ممثل</td>
                      <td>شركة فراند الأعمال للتشغيل</td>
                      <td>ROSE ANNE RAGUINGAN LAGDA</td>
                      <td>قيد النظر</td>
                      <td><a href="#" class="btn btn-info btn-sm">عرض</a></td>
                    </tr>
                    <tr>
                      <td>4571211187</td>
                      <td>1445/01/12</td>
                      <td>المسؤولية عن الحادث</td>
                      <td>وكيل</td>
                      <td>عبدالله سليمان سلامه الدجيل</td>
                      <td>محمد عبدالعزيز محمد الياس</td>
                      <td>محكومة بحكم قطعي</td>
                      <td><a href="#" class="btn btn-info btn-sm">عرض</a></td>
                    </tr>
                    <tr>
                      <td>4571211188</td>
                      <td>1445/03/15</td>
                      <td>النفقة والحضانة</td>
                      <td>مدعي</td>
                      <td>سارة عبد الرحمن</td>
                      <td>محمد بن عبد العزيز</td>
                      <td>قيد النظر</td>
                      <td><a href="#" class="btn btn-info btn-sm">عرض</a></td>
                    </tr>
                    <tr>
                      <td>4571414289</td>
                      <td>1445/06/23</td>
                      <td>حضانة الأطفال</td>
                      <td>مدعي عليه</td>
                      <td>ليلى محمد علي</td>
                      <td>أحمد بن عبد الله</td>
                      <td>قيد النظر</td>
                      <td><a href="#" class="btn btn-info btn-sm">عرض</a></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      @include('admin.partials.footer')
    </div>
  </div>
</main>
<script src="{{ asset('admin-assets/js/core/popper.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/core/bootstrap.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
<script src="{{ asset('admin-assets/js/plugins/chartjs.min.js') }}"></script>
