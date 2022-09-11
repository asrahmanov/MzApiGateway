<nav class="sidebar">
    <div class="sidebar-header">
        <a href="/" class="sidebar-brand">
            {{env('LOGO_NAME')}}<span style="font-size: 15px;"> {{env('LOGO_POSTFIX')}}</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item nav-category">
                    <i class="link-icon" data-feather="box"></i>
                    ГРАЖДАНСКАЯ ПРОД.
                </li>

                <li class="nav-item mt-2 {{ active_class(['project']) }}">
                    <a href="{{ route('project.all') }}" class="nav-link">
                        <span class="link-title">Проекты</span>
                    </a>
                </li>

                {{--        <li class="nav-item {{ active_class(['project/report']) }}">--}}
                {{--            <a href="{{ route('project.report') }}" class="nav-link">--}}
                {{--                <span class="link-title">Отчет по проектам </span>--}}
                {{--            </a>--}}
                {{--        </li>--}}
                <li class="nav-item {{ active_class(['contract']) }}">
                    <a href="{{ route('contract.all') }}" class="nav-link">
                        <span class="link-title">Контракты</span>
                    </a>
                </li>
            @endif
            {{--        <li class="nav-item">--}}
            {{--            <a class="nav-link" data-toggle="collapse" href="#contracts" role="button"  >--}}
            {{--                <span class="link-title">Договоры</span>--}}
            {{--                <i class="link-arrow" data-feather="chevron-down"></i>--}}
            {{--            </a>--}}
            {{--            <div class="collapse" id="contracts">--}}
            {{--                <ul class="nav sub-menu">--}}
            {{--                    <li class="nav-item">--}}
            {{--                        <a href="#" class="nav-link ">Услуги</a>--}}
            {{--                    </li>--}}

            {{--                    <li class="nav-item">--}}
            {{--                        <a href="#" class="nav-link">Продукция</a>--}}
            {{--                    </li>--}}

            {{--                </ul>--}}
            {{--            </div>--}}
            {{--        </li>--}}


{{--                @if (($user['role_id'] ?? "") === 2)--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="../../users/infoOne/{{ $user['id'] }}"--}}
{{--                           class="nav-link {{ active_class(['users/']) }}">Для заполнения</a>--}}
{{--                    </li>--}}
{{--                @endif--}}

{{--                @if (($user['role_id'] ?? "") === 2)--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('users.createUser') }}"--}}
{{--                           class="nav-link {{ active_class(['users/']) }}">Создание пользователя</a>--}}
{{--                    </li>--}}
{{--                @endif--}}


                @if (($user['role_id'] ?? "") === 2)
                    <li class="nav-item nav-category">
                        <i class="link-icon" data-feather="box"></i>
                        Запросы
                    </li>
                    <ul class="nav sub-menu mt-2">
                        <li class="nav-item">
                            <a href="{{ route('interview-worksheets.view') }}"
                               class="nav-link">
                                К исполнению</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('interview-worksheets.view_close') }}"
                               class="nav-link">
                                Завершенные</a>
                        </li>

                    </ul>

                @endif





                @if (($user['role_id'] ?? "") === 7 OR ($user['role_id'] ?? "") === 1)
                    <li class="nav-item nav-category">
                        <i class="link-icon" data-feather="box"></i>
                       Отчеты
                    </li>
                    <ul class="nav sub-menu mt-2">
                        <li class="nav-item">
                            <a href="{{ route('users.viewDzo') }}"
                               class="nav-link {{ active_class(['users/create']) }}">
                                Ответственные</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('interview-worksheets.all') }}"
                               class="nav-link">
                                Отчеты</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('interview-form.all') }}"
                               class="nav-link">
                                Виды форм</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('interview-form.create') }}"
                               class="nav-link">
                                Создание формы</a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('interview-worksheets.createWorkSheets') }}"
                               class="nav-link">
                                Отправка заданий</a>
                        </li>

                    </ul>

                @endif


                @if (($user['role_id'] ?? "") === 7 OR ($user['role_id'] ?? "") === 1)
                    <li class="nav-item nav-category">
                        <i class="link-icon" data-feather="box"></i>
                        1С
                    </li>
                    <ul class="nav sub-menu mt-2">
                        <li class="nav-item">
                            <a href="{{ route('data-aggregation.dashboardOne') }}"
                               class="nav-link {{ active_class(['data-aggregation/dashboardOne']) }}">
                                Дашборд 1</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('data-aggregation.dashboardTwo') }}"
                               class="nav-link {{ active_class(['data-aggregation/dashboardTwo']) }}">
                                Дашборд 2</a>
                        </li>
                    </ul>

                @endif



            @if (($user['role_id'] ?? "") === 1)
{{--                <li class="nav-item ">--}}
{{--                    <a href="{{ route('project.product') }}" class="nav-link">--}}
{{--                        <span class="link-title">Продукты</span>--}}
{{--                    </a>--}}
{{--                </li>--}}



                <li class="nav-item nav-category">
                    <i class="link-icon" data-feather="box"></i>
                    БЕЗОПАСНОСТЬ
                </li>



                <li class="nav-item {{ active_class(['security-report/viewReport']) }}">
                    <a href="{{ route('security-report.viewReport') }}" class="nav-link">
                        <span class="link-title">Все отчеты</span>
                    </a>
                </li>


                <li class="nav-item {{ active_class(['security-report/index']) }}">
                    <a href="{{ route('security-report.index') }}" class="nav-link">
                        <span class="link-title">Создание отчета</span>
                    </a>
                </li>


            @endif
            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item nav-category">
                    <i class="link-icon" data-feather="box"></i>
                    СПРАВОЧНИКИ
                </li>


                <li class="nav-item  mt-2 {{ active_class(['companies']) }}">
                    <a href="{{ route('companies.all') }}" class="nav-link">
                        <span class="link-title">ДЗО</span>
                    </a>
                </li>
            @endif




                @if (($user['role_id'] ?? "") === 1)
                    <li class="nav-item {{ active_class(['users/*']) }}">
                        <a class="nav-link" data-toggle="collapse" href="#users" role="button"
                           aria-expanded="{{ is_active_route(['users/*']) }}" aria-controls="manuals">
                            <span class="link-title">Пользователи</span>
                            <i class="link-arrow" data-feather="chevron-down"></i>
                        </a>
                        <div class="collapse {{ show_class(['users/*']) }}" id="users">
                            <ul class="nav sub-menu">
                                <li class="nav-item">
                                    <a href="{{ route('users.view') }}"
                                       class="nav-link {{ active_class(['users/']) }}">Все пользователи</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('users.createView') }}"
                                       class="nav-link {{ active_class(['users/create']) }}">Создание
                                        пользователя</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{ route('users.viewDzo') }}"
                                       class="nav-link {{ active_class(['users/create']) }}">
                                        Ответственные</a>
                                </li>

                            </ul>
                        </div>
                    </li>
                @endif


            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item {{ active_class(['manuals/*']) }}">
                    <a class="nav-link" data-toggle="collapse" href="#directory" role="button"
                       aria-expanded="{{ is_active_route(['manuals/*']) }}" aria-controls="manuals">
                        <span class="link-title">Контрагенты</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['manuals/*']) }}" id="directory">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('manuals.counterparties.all') }}"
                                   class="nav-link {{ active_class(['manuals/counterparties']) }}">Все контрагенты</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('manuals.counterparties.create') }}"
                                   class="nav-link {{ active_class(['manuals/counterparties']) }}">Создание
                                    Контрагента</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif
            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item  {{ active_class(['project/stage']) }}">
                    <a href="{{ route('project.stage') }}" class="nav-link">
                        <span class="link-title">Этапы проектов</span>
                    </a>
                </li>

                <li class="nav-item  {{ active_class(['project/status']) }}">
                    <a href="{{ route('project.status') }}" class="nav-link">
                        <span class="link-title">Статусы проектов</span>
                    </a>
                </li>


                <li class="nav-item  {{ active_class(['project/type']) }}">
                    <a href="{{ route('project.type') }}" class="nav-link">
                        <span class="link-title">Типы проектов</span>
                    </a>
                </li>

                <li class="nav-item  {{ active_class(['project/productList']) }}">
                    <a href="{{ route('project.productList') }}" class="nav-link">
                        <span class="link-title">Продукция</span>
                    </a>
                </li>
            @endif
            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item nav-category">
                    <i class="link-icon" data-feather="box"></i>
                    HR
                </li>



                <li class="nav-item {{ active_class(['hr-report*']) }}">
                    <a class="nav-link" data-toggle="collapse" href="#directory" role="button"
                       aria-expanded="{{ is_active_route(['hr/*']) }}" aria-controls="manuals">
                        <span class="link-title">Сдать отчеты</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['hr-report*']) }}" id="directory">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('hr-report.index') }}"
                                   class="nav-link {{ active_class(['hr-report/index']) }}">Отчет по дивизиону</a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('hr-report-covid.covid') }}"
                                   class="nav-link {{ active_class(['hr-report-covid/addReport']) }}">Отчет по
                                    заболевшим</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (($user['role_id'] ?? "") === 1)
                <li class="nav-item {{ active_class(['hr-report*']) }}">
                    <a class="nav-link" data-toggle="collapse" href="#directory" role="button"
                       aria-expanded="{{ is_active_route(['hr/hr-report*']) }}" aria-controls="manuals">
                        <span class="link-title">Просмотр отчеты</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['hr-report*']) }}" id="directory">
                        <ul class="nav sub-menu">
                            <li class="nav-item">
                                <a href="{{ route('hr-report.view') }}"
                                   class="nav-link {{ active_class(['hr-report/report_view']) }}">Отчет по дивизиону</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('hr-report-covid.report_covid') }}"
                                   class="nav-link {{ active_class(['hr-report-covid/viewReport']) }}">Отчет по
                                    заболевшим</a>
                            </li>

                        </ul>
                    </div>
                </li>
            @endif

            @if (($user['role_id'] ?? "") === 1 OR ($user['role_id'] ?? "") === 5 OR ($user['role_id'] ?? "") === 6 OR ($user['role_id'] ?? "") === 7)
                <li class="nav-item nav-category">
                    <i class="link-icon" data-feather="box"></i>
                    Производ. показатели
                </li>

                <li class="nav-item {{ active_class(['production-report/*']) }}">
                    <a class="nav-link" data-toggle="collapse" href="#production" role="button"
                       aria-expanded="{{ is_active_route(['production-report/*']) }}" aria-controls="manuals">
                        <span class="link-title">Отчеты</span>
                        <i class="link-arrow" data-feather="chevron-down"></i>
                    </a>
                    <div class="collapse {{ show_class(['production-report/*']) }}" id="production">
                        <ul class="nav sub-menu">
                            @if (($user['role_id'] ?? "") === 1 OR ($user['role_id'] ?? "") === 5 )
                            <li class="nav-item">
                                <a href="{{ route('production-report.addReport') }}"
                                   class="nav-link {{ active_class(['production-report/addReport']) }}">Сдать отчет</a>
                            </li>
                            @endif

                            @if (($user['role_id'] ?? "") === 1 OR ($user['role_id'] ?? "") === 5 )
                            <li class="nav-item">
                                <a href="{{ route('production-report.report_view') }}"
                                   class="nav-link {{ active_class(['production/report_view']) }}">Посмотреть отчет</a>
                            </li>
                            @endif

                            @if (($user['role_id'] ?? "") === 1 OR ($user['role_id'] ?? "") === 5  OR ($user['role_id'] ?? "") === 6 OR ($user['role_id'] ?? "") === 7)
                                <li class="nav-item">
                                    <a href="{{ route('production-report.dashBoard') }}"
                                       class="nav-link {{ active_class(['production/dashBoard']) }}">Дашборд</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</nav>
