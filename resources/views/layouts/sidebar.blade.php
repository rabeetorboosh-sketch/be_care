<link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">

<aside class="sidebar show" id="sidebar">
    <h2 class="logo">نظام الصيانة </h2>


    <ul class="menu">


        <li class="menu-item">

            <button class="dropdown-btn">المدخلات العامة </button>
            <ul class="submenu">

                <li class="menu-item">
                    <button class="dropdown-btn"> الــصــنـاديــق </button>
                    <ul class="submenu">

                        <li><a href="{{route('cash_boxes.create')}}"> إضافة صنـــدوق</a></li>
                        <li><a href="{{route('cash_boxes.index')}}">قائمة الصناديق</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> الـــمـــخــــازن </button>
                    <ul class="submenu">

                        <li><a href="{{route('warehouses.create')}}"> إضافة مـــخــزن</a></li>
                        <li><a href="{{route('warehouses.index')}}">قائمة الـمـخـازن</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> الـــــوحـــــدات </button>
                    <ul class="submenu">

                        <li><a href="{{route('units.create')}}"> إضافة وحـــــدة</a></li>
                        <li><a href="{{route('units.index')}}">قائمة الوحــدات</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> الاصــــــنــــاف </button>
                    <ul class="submenu">

                        <li><a href="{{route('items.create')}}"> إضافة صـــنــف</a></li>
                        <li><a href="{{route('items.index')}}">قائمة الاصــنـاف</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> الـــعـــمــــلاء </button>
                    <ul class="submenu">

                        <li><a href="{{route('customers.create')}}"> إضـافة عـمـيل</a></li>
                        <li><a href="{{route('customers.index')}}">قائمة العملاء</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> الـــمــورديـــن </button>
                    <ul class="submenu">

                        <li><a href="{{route('suppliers.create')}}"> إضـافة مـــورد</a></li>
                        <li><a href="{{route('suppliers.index')}}">قائمة الموردين</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> انواع الاجهزة </button>
                    <ul class="submenu">

                        <li><a href="{{route('device-types.create')}}"> إضافة نـــوع</a></li>
                        <li><a href="{{route('device-types.index')}}">قائمة الانواع</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn">  قطع الصيانة   </button>
                    <ul class="submenu">

                        <li><a href="{{route('parts.create')}}"> إضافة قــطعة</a></li>
                        <li><a href="{{route('parts.index')}}">قائمة القطع</a></li>

                    </ul>

                </li>

            </ul>
        </li>
        <li class="menu-item">

            <button class="dropdown-btn"> الـــصــــيـــانـــــة   </button>
            <ul class="submenu">

                <li class="menu-item">
                    <button class="dropdown-btn"> استلام اجهزة  </button>
                    <ul class="submenu">

                        <li><a href="{{route('devices.create')}}"> إضافة اســــتلام</a></li>
                        <li><a href="{{route('devices.index')}}">قائمة الاستلامات</a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn">  فاتورة صيانة    </button>
                    <ul class="submenu">

                        <li><a href="{{route('repair_invoices.create')}}"> إضافة فاتورة صيانة</a></li>
                        <li><a href="{{route('repair_invoices.index')}}">قائمة فواتير الصيانة</a></li>

                    </ul>

                </li>




            </ul>
        </li>
        <li class="menu-item">

            <button class="dropdown-btn">  الــحـــســـابـــات      </button>
            <ul class="submenu">

                <li class="menu-item">
                    <button class="dropdown-btn"> سندات  القبض  </button>
                    <ul class="submenu">

                        <li><a href="{{route('receipts.create')}}"> إضافة سنــد قـبـض</a></li>
                        <li><a href="{{route('receipts.index')}}">قائمة سندات القبض </a></li>

                    </ul>

                </li>
                <li class="menu-item">
                    <button class="dropdown-btn"> سندات  الصرف  </button>
                    <ul class="submenu">

                        <li><a href="{{route('receipts_out.create')}}"> إضافة سنــد صــرف</a></li>
                        <li><a href="{{route('receipts_out.index')}}">قائمة سندات القبض </a></li>

                    </ul>

                </li>





            </ul>
        </li>
        <li class="menu-item">

            <button class="dropdown-btn">  الــعـــمــلــيـات      </button>
            <ul class="submenu">

                <li class="menu-item">
                    <button class="dropdown-btn">     فواتير الشراء  </button>
                    <ul class="submenu">

                        <li><a href="{{route('purchase_invoices.create')}}"> إضافة فاتورة مـشـتـريات</a></li>
                        <li><a href="{{route('purchase_invoices.index')}}">قائمة فواتير المشتريات </a></li>

                    </ul>

                </li>






            </ul>
        </li>

    </ul>

    <script src="{{ asset('js/sidebar.js') }}"></script>

</aside>
