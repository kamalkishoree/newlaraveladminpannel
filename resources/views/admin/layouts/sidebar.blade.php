<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- <div class="p_15">
    <div class="user_prof">
        <div class="img_prof">
        <img class="img-fluid" src="/assets/admin/img/default-user.png" alt="Logo">
        </div>
        <div class="content_prof">
            <h6>Kenonn Rawat</h6>
            <p>welcome</p>
        </div>
    </div>
    </div> -->


    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!-- Dashboard -->
                <li class="{{ (request()->is('admin/dashboard*')) ? 'active' : '' }}">
                    <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i data-feather="home"></i>
                        <!-- <i class="fa-solid fa-house"></i> -->
                        <span>
                            {{__('sidebar.dashboard')}}
                        </span>
                    </a>
                </li>


                <!-- /Dashboard -->

            <!-- Users -->
            @if(auth()->user()->can('user-list') || auth()->user()->can('role-list') || auth()->user()->can('permission-list') || auth()->user()->can('user-activity'))
                <!-- CMS -->
          
          
                @if(auth()->user()->can('cmspage-list') || auth()->user()->can('cmscategory-list'))
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="users"></i>
                        <span class="hide-menu">{{__('User Management')}} </span>
                        <span class="menu-arrow"></span>
                    </a>


                    <ul style="display: none;">
                        @can('user-list')
                        <li>
                            <a href="{{ route('users.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/user*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.user')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('role-list')
                        <li>
                            <a href="{{ route('roles.index') }}" title="{{__('sidebar.roles')}}" class="sidebar-link {{ (request()->is('admin/roles*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.roles')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('permission-list')
                        <li>
                            <a href="{{ route('permissions.index') }}" title="{{__('sidebar.permissions')}}" class="sidebar-link {{ (request()->is('admin/permissions*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.permission')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('user-activity')
                        <li>
                            <a href="/admin/user-activity" title="{{__('sidebar.user-activity')}}" class="sidebar-link {{ (request()->is('admin/setting/useractivity*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.user-activity')}}</span>
                            </a>
                        </li>
                        @endcan

                        <!-- new static add -->
                        @can('user-activity')
                        <li>
                            <a href="/admin/user-activity" title="{{__('sidebar.user-activity')}}" class="sidebar-link {{ (request()->is('admin/setting/useractivity*')) ? 'active' : '' }}">
                                <span class="hide-menu">User KYC Data</span>
                            </a>
                        </li>
                        @endcan
                        <!-- end -->
                    </ul>





                    <ul style="display: none;">
                        @can('user-list')
                        <li>
                            <a href="{{ route('users.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/user*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Review Purchase Details')}}</span>
                            </a>
                        </li>
                        @endcan

                       
                    </ul>
                </li>
                @endif
                <!-- /Users -->
  

                <!-- API Inigration -->

                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="users"></i>
                        <span class="hide-menu">{{__('Affiliate Integration')}} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Integration Api keys')}}</span>
                            </a>
                        </li>
                    </ul> 
                </li>
                
                <!---->


            <li class="{{ (request()->is('admin/campaign*')) ? '' : '' }}">
                       <a class="sidebar-link" href="{{ route('campaign.index') }}" aria-expanded="false">
                       <i class="fa-solid fa-arrow-right-from-bracket"></i>
             <span>
                         {{__('All Clicks')}}
             </span>
                  </a>
            </li>


            <li class="{{ (request()->is('admin/push-notification*')) ? '' : '' }}">
                       <a class="sidebar-link" href="{{ route('pushNotification.index') }}" aria-expanded="false">
                       <i class="fa-solid fa-arrow-right-from-bracket"></i>
             <span>
                         {{__('Push Notifications')}}
             </span>
                  </a>
            </li>



                <!-- Manage Commission -->
                <li class="{{ (request()->is('admin/dashboard*')) ? '' : '' }}">
                <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                    <i data-feather="book-open"></i>
                    
                    <span>
                        {{__('Manage Commission ')}}
                    </span>
                </a>
                </li>
                <!-- /Manage Commission -->

                   <!-- Banner -->
                <li class="{{ (request()->is('admin/banner*')) ? '' : '' }}">
                <a class="sidebar-link" href="{{ route('banner.index') }}" aria-expanded="false">
                <i class="fa-regular fa-images"></i>
                    <span>
                        {{__('Banners ')}}
                    </span>
                </a>
                </li>
                <!-- /banner -->

                <!-- add static new Wallet -->
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa-regular fa-credit-card"></i>
                        <span class="hide-menu">Wallet </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.wallet')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Total Funds</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.wallet')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Total Penny Drop</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.wallet')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Total Profit</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('wallet.index') }}" title="{{__('sidebar.wallet')}}" class="sidebar-link {{ (request()->is('admin/wallet*')) ? 'active' : '' }}">
                                <span class="hide-menu">Users Wallet</span>
                            </a>
                        </li>

                    </ul> 
                </li>

                <!-- end -->


                 <!-- add static new Campaigns -->
                 <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa-regular fa-credit-card"></i>
                        <span class="hide-menu">Campaigns </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('brand.index') }}" title="{{__('Brands')}}" class="sidebar-link {{ (request()->is('admin/brand*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Cashback Offers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coupon.index') }}" title="{{__('Coupons')}}" class="sidebar-link {{ (request()->is('admin/coupon*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('deal.index') }}" title="{{__('Deals')}}" class="sidebar-link {{ (request()->is('admin/deal*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Deals</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('category.index') }}" title="{{__('Cayegories')}}" class="sidebar-link {{ (request()->is('admin/category*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Category</span>
                            </a>
                        </li>
                    </ul> 
                </li>

                <!-- end -->

                <!-- add static new Campaigns -->
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa-regular fa-credit-card"></i>
                        <span class="hide-menu">Campaigns Reports </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Cashback</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Coupons</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Deals</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Financial</span>
                            </a>
                        </li>
                    </ul> 
                </li>

                <!-- end -->


                    <!-- add static new Financial offers -->
                    <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                    <i class="fa-regular fa-credit-card"></i>
                        <span class="hide-menu">Financial Offers </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('financial-category.index') }}" title="{{__('financial-category')}}" class="sidebar-link {{ (request()->is('admin/financial-category*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Category </span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('financial-offer.index') }}" title="{{__('financial-offer')}}" class="sidebar-link {{ (request()->is('admin/financial-offer*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Offers</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Create Activity</span>
                            </a>
                        </li>
                       
                    </ul> 
                </li>

                <!-- end -->


                <!-- Withdrawal management -->
   
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="file-text"></i>
                        <span class="hide-menu">{{__('Withdrawal Mgmt')}} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        @can('cmscategory-list')
                        <li>
                            <a href="{{ route('cmscategories.index') }}" title="{{__('sidebar.category')}}" class="sidebar-link {{ (request()->is('admin/cmscategories*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Withdrawal Type Mgmt')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('withdrawal.index') }}" title="{{__('withdrawal Request')}}" class="sidebar-link {{ (request()->is('admin/withdrawal*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Withdrawal Request')}}</span>
                            </a>
                        </li>
                        @endcan
                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('cmspages.index') }}" title="{{__('sidebar.cms-pages')}}" class="sidebar-link {{ (request()->is('admin/cmspage*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Offer Mgmt')}}</span>
                            </a>
                        </li>
                        @endcan

                        <!-- new added -->
                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('withdrawal.index',['status' => 'pending']) }}" title="{{__('withdrawal Request')}}" class="sidebar-link {{ (request()->is('admin/withdrawal*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Pending Withdrawals')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('cmspages.index') }}" title="{{__('sidebar.cms-pages')}}" class="sidebar-link {{ (request()->is('admin/cmspage*')) ? 'active' : '' }}">
                                <span class="hide-menu">Terms</span>
                            </a>
                        </li>
                        @endcan

                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('cmspages.index') }}" title="{{__('sidebar.cms-pages')}}" class="sidebar-link {{ (request()->is('admin/cmspage*')) ? 'active' : '' }}">
                                <span class="hide-menu">Auto Withdrawals</span>
                            </a>
                        </li>
                        @endcan
                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('cmspages.index') }}" title="{{__('sidebar.cms-pages')}}" class="sidebar-link {{ (request()->is('admin/cmspage*')) ? 'active' : '' }}">
                                <span class="hide-menu">Withdrawal permission</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!-- /Withdrawal management -->

                <!-- Financial Redirection Management   -->
                <li class="{{ (request()->is('admin/dashboard*')) ? '' : '' }}">
                                <a class="sidebar-link" href="{{ route('dashboard') }}" aria-expanded="false">
                                <i class="fa-solid fa-indian-rupee-sign"></i>
                    <span>
                        {{__('Financial Redirection Mgmt   ')}}
                    </span>
                </a>
                </li>
                <!-- /Financial Redirection Management   -->

                 <!--Reports   -->
                {{-- <li class="{{ (request()->is('admin/report*')) ? '' : '' }}">
                                <a class="sidebar-link" href="{{ route('report.index') }}" aria-expanded="false">
                                <i data-feather="file-text"></i>
                                <!-- <i class="fa-regular fa-file-lines"></i> -->
                    <span>
                        {{__('Reports ')}}
                    </span>
                --}}
                </a>
                </li>
                <!-- /Reports    -->
                 
                <!-- add static new Reports -->
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                    <i data-feather="file-text"></i>
                        <span class="hide-menu">Reports </span>
                       
                    </a>

                    <ul style="display: none;">
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">All Clicks Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">All Leads Report</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('affiliate.index') }}" title="{{__('sidebar.user')}}" class="sidebar-link {{ (request()->is('admin/affiliate*')) ? 'active' : '' }}">
                                <span class="hide-menu">Performance Report</span>
                            </a>
                        </li>
                    </ul> 
                </li>

                <!-- end -->



                <!-- CMS -->
                @if(auth()->user()->can('cmspage-list') || auth()->user()->can('cmscategory-list'))
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i data-feather="file-text"></i> -->
                        <i class="fa-solid fa-list"></i>
                        <span class="hide-menu">{{__('sidebar.cms')}} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                        @can('cmscategory-list')
                        <li>
                            <a href="{{ route('cmscategories.index') }}" title="{{__('sidebar.category')}}" class="sidebar-link {{ (request()->is('admin/cmscategories*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.category')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('cmspage-list')
                        <li>
                            <a href="{{ route('cmspages.index') }}" title="{{__('sidebar.cms-pages')}}" class="sidebar-link {{ (request()->is('admin/cmspage*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.cms-pages')}}</span>
                            </a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endif
                <!-- /CMS -->

    

                <!-- Settings -->
                @if(auth()->user()->can('file-manager') || auth()->user()->can('currency-list') || auth()->user()->can('websetting-edit') || auth()->user()->can('log-view'))
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <i data-feather="settings"></i>
                        <span class="hide-menu">{{__('sidebar.settings')}} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                    @can('websetting-edit')
                        <li>
                            <a href="{{route('website-setting.edit')}}" title="{{__('sidebar.website-setting')}}" class="sidebar-link {{ (request()->is('admin/setting/website-setting*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.website-setting')}}</span>
                            </a>
                        </li>
                  @endcan

                {{--
        
                        @can('currency-list')
                        <li>
                            <a href="{{ route('currencies.index') }}" title="{{__('sidebar.currencies')}}" class="sidebar-link {{ (request()->is('admin/currencies*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.currency')}}</span>
                            </a>
                        </li>
                        @endcan

                     

                        @can('file-manager')
                        <li>
                            <a href="{{route('filemanager.index')}}" title="{{__('sidebar.file-manager')}}" class="sidebar-link {{ (request()->is('admin/setting/file-manager*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.file-manager')}}</span>
                            </a>
                        </li>
                        @endcan

                        @can('log-view')
                        <li>
                            <a href="/admin/log-reader" title="{{__('sidebar.read-logs')}}" class="sidebar-link {{ (request()->is('admin/setting/log*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.read-logs')}}</span>
                            </a>
                        </li>
                        @endcan
                        --}}
                    </ul>
                </li>
                @endif
              
              {{--  <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                        <!-- <i data-feather="configuration"></i> -->
                        <i class="fa-solid fa-wrench"></i>
                        <span class="hide-menu">{{__('sidebar.configuration')}} </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul style="display: none;">
                    <li>
                            <a href="/admin/sms/index" title="{{__('sidebar.msg-gateways')}}" class="sidebar-link {{ (request()->is('admin/sms*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('sidebar.msg-gateways')}}</span>
                            </a>
                        </li>
                    </ul>
                </li>
                --}}
            {{--
                @if(isModuleEnabled('WebRTCAudioVideoChat'))
                @if(auth()->user()->can('user-chat'))
                <li class="submenu">
                    <a class="" href="javascript:void(0)" aria-expanded="false">
                <i class="fa-brands fa-rocketchat"></i>
                        <span class="hide-menu">{{__('Chat')}} </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul style="display: none;">
                        <li>
                            <a href="{{ url('/admin/webrtcaudiovideochat/ChatRoom') }}" title="{{__('chat')}}" class="sidebar-link {{ (request()->is('admin/webrtcaudiovideochat/ChatRoom*')) ? 'active' : '' }}">
                                <span class="hide-menu">{{__('Chat Room')}}</span>
                            </a>
                        </li>



                    </ul>
                </li>
                @endif
                @endif
                --}}  
                <!-- /Settings -->
                <!--Logout   -->
                <li class="{{ (request()->is('admin/dashboard*')) ? '' : '' }}">
                                <a class="sidebar-link" href="{{ route('logout') }}" aria-expanded="false">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>
                        {{__('Logout  ')}}
                    </span>
                </a>
                </li>
                <!-- /logout    -->

            </ul>
        </div> <!-- /Sidebar-Menu -->
    </div> <!-- /Sidebar-inner -->
</div><!-- /Sidebar -->