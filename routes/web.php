<?php

use Illuminate\Support\Facades\Route;

Route::get('/clear', function(){
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
	 \Illuminate\Support\Facades\Artisan::call('view:clear');
});


Route::get('rewards_and_points', function(){
    return view('templates.basic.blog');
});
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/cron', 'CronController@cron')->name('bv.matching.cron');
Route::get('/issue_weekly_bonus', 'CronController@issue_weekly_bonus');

Route::namespace('Gateway')->prefix('ipn')->name('ipn.')->group(function () {
    Route::post('paypal', 'Paypal\ProcessController@ipn')->name('Paypal');
    Route::get('paypal-sdk', 'PaypalSdk\ProcessController@ipn')->name('PaypalSdk');
    Route::post('perfect-money', 'PerfectMoney\ProcessController@ipn')->name('PerfectMoney');
    Route::post('stripe', 'Stripe\ProcessController@ipn')->name('Stripe');
    Route::post('stripe-js', 'StripeJs\ProcessController@ipn')->name('StripeJs');
    Route::post('stripe-v3', 'StripeV3\ProcessController@ipn')->name('StripeV3');
    Route::post('skrill', 'Skrill\ProcessController@ipn')->name('Skrill');
    Route::post('paytm', 'Paytm\ProcessController@ipn')->name('Paytm');
    Route::post('payeer', 'Payeer\ProcessController@ipn')->name('Payeer');
    Route::post('paystack', 'Paystack\ProcessController@ipn')->name('Paystack');
    Route::post('voguepay', 'Voguepay\ProcessController@ipn')->name('Voguepay');
    Route::get('flutterwave/{trx}/{type}', 'Flutterwave\ProcessController@ipn')->name('Flutterwave');
    Route::post('razorpay', 'Razorpay\ProcessController@ipn')->name('Razorpay');
    Route::post('instamojo', 'Instamojo\ProcessController@ipn')->name('Instamojo');
    Route::get('blockchain', 'Blockchain\ProcessController@ipn')->name('Blockchain');
    Route::get('blockio', 'Blockio\ProcessController@ipn')->name('Blockio');
    Route::post('coinpayments', 'Coinpayments\ProcessController@ipn')->name('Coinpayments');
    Route::post('coinpayments-fiat', 'Coinpayments_fiat\ProcessController@ipn')->name('CoinpaymentsFiat');
    Route::post('coingate', 'Coingate\ProcessController@ipn')->name('Coingate');
    Route::post('coinbase-commerce', 'CoinbaseCommerce\ProcessController@ipn')->name('CoinbaseCommerce');
    Route::get('mollie', 'Mollie\ProcessController@ipn')->name('Mollie');
    Route::post('cashmaal', 'Cashmaal\ProcessController@ipn')->name('Cashmaal');
    Route::post('authorize-net', 'AuthorizeNet\ProcessController@ipn')->name('AuthorizeNet');
    Route::post('2check-out', 'TwoCheckOut\ProcessController@ipn')->name('TwoCheckOut');
    Route::post('mercado-pago', 'MercadoPago\ProcessController@ipn')->name('MercadoPago');
});

// User Support Ticket
Route::prefix('ticket')->group(function () {
    Route::get('/', 'TicketController@supportTicket')->name('ticket');
    Route::get('/new', 'TicketController@openSupportTicket')->name('ticket.open');
    Route::post('/create', 'TicketController@storeSupportTicket')->name('ticket.store');
    Route::get('/view/{ticket}', 'TicketController@viewTicket')->name('ticket.view');
    Route::post('/reply/{ticket}', 'TicketController@replyTicket')->name('ticket.reply');
    Route::get('/download/{ticket}', 'TicketController@ticketDownload')->name('ticket.download');
});

/*
|--------------------------------------------------------------------------
| Start Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/', 'LoginController@showLoginForm')->name('login');
        Route::post('/', 'LoginController@login')->name('login');
        Route::get('logout', 'LoginController@logout')->name('logout');
        // Admin Password Reset
        Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.reset');
        Route::post('password/reset', 'ForgotPasswordController@sendResetCodeEmail');
        Route::post('password/verify-code', 'ForgotPasswordController@verifyCode')->name('password.verify.code');
        Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset.form');
        Route::post('password/reset/change', 'ResetPasswordController@reset')->name('password.change');
    });

    Route::middleware(['admin'])->group(function () {
        Route::get('clear-company-wallet', 'AdminController@clear_company_wallet')->name('wallet-clear');

        Route::get('dashboard', function(){
           return redirect('/admin/company-dashboard');
        });// 'AdminController@dashboard')->name('dashboard');
        Route::get('summary', 'AdminController@summary')->name('summary');
        Route::get('warehouse-products', 'AdminController@warehouse_products')->name('warehouse.products');
        Route::get('office-statements', 'AdminController@office_statements')->name('office.statements');
        Route::post('summary', 'AdminController@summary')->name('summary');
		
		Route::get('lucky-draw-promos-winners', 'AdminController@lucky_draws')->name('lucky_draws');
		Route::get('lucky-draw-promos-all', 'AdminController@lucky_draw_all')->name('lucky_draw_all');
		Route::post('lucky-draw-promo-winner', 'AdminController@lucky_draw_complete')->name('lucky_draw_complete');
       
        Route::get('company-dashboard', 'AdminController@company_dashboard')->name('dashboard.company');
        Route::get('users-dashboard', 'AdminController@users_dashboard')->name('dashboard.users');
        Route::get('expense-dashboard', 'AdminController@expense_dashboard')->name('dashboard.expense');
       
        Route::get('profile', 'AdminController@profile')->name('profile');
        Route::post('profile', 'AdminController@profileUpdate')->name('profile.update');
        Route::get('password', 'AdminController@password')->name('password');
        Route::post('password', 'AdminController@passwordUpdate')->name('password.update');

        //Notification
        Route::get('notifications','AdminController@notifications')->name('notifications');
        Route::get('notification/read/{id}','AdminController@notificationRead')->name('notification.read');
        Route::get('notifications/read-all','AdminController@readAll')->name('notifications.readAll');

        // mlm plan
        Route::get('plans', 'MlmController@plan')->name('plan');
        Route::post('plan/store', 'MlmController@planStore')->name('plan.store');
        Route::post('plan/update', 'MlmController@planUpdate')->name('plan.update');
		
		//rewards management
		Route::get('rewards-users', 'AdminController@reward_users')->name('reward-users');
        Route::post('deliver-reward', 'AdminController@deliver_reward')->name('deliver-reward');

        // mlm stockist
        //Route::get('stockists', 'MlmController@stockist')->name('stockist');
        //Route::post('stockist/store', 'MlmController@stockistStore')->name('stockist.store');
        //Route::post('stockist/update', 'MlmController@stockistUpdate')->name('stockist.update');

        // matching bonus
        Route::post('matching-bonus/update', 'MlmController@matchingUpdate')->name('matching-bonus.update');

        // tree
        Route::get('/tree/{id}', 'ManageUsersController@tree')->name('users.single.tree');
        Route::get('/user/tree/{user}', 'ManageUsersController@otherTree')->name('users.other.tree');
        Route::get('/user/tree/search', 'ManageUsersController@otherTree')->name('users.other.tree.search');

        Route::get('notice', 'GeneralSettingController@noticeIndex')->name('setting.notice');
        Route::post('notice/update', 'GeneralSettingController@noticeUpdate')->name('setting.notice.update');

		//============== start the Pin ===========
        Route::get('stockists','MlmController@stockists')->name('stockists');
        Route::post('stockist/store', 'MlmController@stockistStore')->name('stockist.store');
        Route::post('stockist/update', 'MlmController@stockistUpdate')->name('stockist.update');
        Route::get('stockist/list', 'MlmController@stockistList')->name('stockist.list');
		
		//============== start the membership ===========

        Route::get('memberShip','MlmController@memberShip')->name('memberShip');
        Route::post('memberShip_Store','MlmController@memberShip_Store')->name('membership.store');
        Route::post('memberShip_update','MlmController@memberShip_update')->name('membership.update');

        //============== start the category route ===========
        Route::name('category.')->prefix('category')->group(function () {
            Route::get('/', 'CategoryController@index')->name('index');
            Route::post('/store', 'CategoryController@store')->name('store');
            Route::post('/update/{id}', 'CategoryController@update')->name('update');
            Route::get('/status/change/{id}', 'CategoryController@changeStatus')->name('status.change');
        });

         //============== start the product route ===========
         Route::name('product.')->prefix('product')->group(function () {
            Route::get('/', 'ProductController@index')->name('index');
            Route::get('/create', 'ProductController@create')->name('create');
            Route::post('/store', 'ProductController@store')->name('store');
            Route::get('/edit/{id}', 'ProductController@edit')->name('edit');
            Route::post('/update/{id}', 'ProductController@update')->name('update');
            Route::get('/status/change/{id}', 'ProductController@changeStatus')->name('status.change');
            Route::get('remove-image/{id}/{key}', 'ProductController@removeImage')->name('image.remove');
        });


        //Report Bugs
        Route::get('request-report','AdminController@requestReport')->name('request.report');
        Route::post('request-report','AdminController@reportSubmit');

        Route::get('system-info','AdminController@systemInfo')->name('system.info');
		
		
		Route::get('calculator','AdminController@calculator')->name('calculator');


        // Users Manager
        Route::get('users', 'ManageUsersController@allUsers')->name('users.all');
        Route::get('users/active', 'ManageUsersController@activeUsers')->name('users.active');
        Route::get('users/banned', 'ManageUsersController@bannedUsers')->name('users.banned');
        Route::get('users/email-verified', 'ManageUsersController@emailVerifiedUsers')->name('users.email.verified');
        Route::get('users/email-unverified', 'ManageUsersController@emailUnverifiedUsers')->name('users.email.unverified');
        Route::get('users/sms-unverified', 'ManageUsersController@smsUnverifiedUsers')->name('users.sms.unverified');
        Route::get('users/sms-verified', 'ManageUsersController@smsVerifiedUsers')->name('users.sms.verified');
        Route::get('users/with-balance', 'ManageUsersController@usersWithBalance')->name('users.with.balance');

        Route::get('users/{scope}/search', 'ManageUsersController@search')->name('users.search');
        Route::get('user/detail/{id}', 'ManageUsersController@detail')->name('users.detail');
        Route::post('user/update/{id}', 'ManageUsersController@update')->name('users.update');
        Route::post('user/add-sub-balance/{id}', 'ManageUsersController@addSubBalance')->name('users.add.sub.balance');
        Route::get('user/send-email/{id}', 'ManageUsersController@showEmailSingleForm')->name('users.email.single');
        Route::post('user/send-email/{id}', 'ManageUsersController@sendEmailSingle')->name('users.email.single');
        Route::get('user/login/{id}', 'ManageUsersController@login')->name('users.login');
        Route::get('user/transactions/{id}', 'ManageUsersController@transactions')->name('users.transactions');
        Route::get('user/deposits/{id}', 'ManageUsersController@deposits')->name('users.deposits');
        Route::get('user/deposits/via/{method}/{type?}/{userId}', 'ManageUsersController@depositViaMethod')->name('users.deposits.method');
        Route::get('user/withdrawals/{id}', 'ManageUsersController@withdrawals')->name('users.withdrawals');
        Route::get('user/withdrawals/via/{method}/{type?}/{userId}', 'ManageUsersController@withdrawalsViaMethod')->name('users.withdrawals.method');
        // Login History
        Route::get('users/login/history/{id}', 'ManageUsersController@userLoginHistory')->name('users.login.history.single');

        Route::get('users/send-email', 'ManageUsersController@showEmailAllForm')->name('users.email.all');
        Route::post('users/send-email', 'ManageUsersController@sendEmailAll')->name('users.email.send');
        Route::get('users/email-log/{id}', 'ManageUsersController@emailLog')->name('users.email.log');
        Route::get('users/email-details/{id}', 'ManageUsersController@emailDetails')->name('users.email.details');

        Route::get('user/referral/{id}', 'ManageUsersController@userRef')->name('users.ref');
        
        Route::get('user/approved-sellers', 'ManageUsersController@approvedSellers')->name('users.approvedSellers');
        Route::get('user/pending-sellers', 'ManageUsersController@pendingSellers')->name('users.pendingSellers');
        Route::get('user/pos-Orders', 'ManageUsersController@posOrders')->name('users.posOrders');
        Route::get('user/default-Orders', 'ManageUsersController@defaultOrders')->name('users.defaultOrders');
        Route::get('user/admin-products', 'ManageUsersController@adminProducts')->name('users.adminProducts');
        Route::get('user/seller-products', 'ManageUsersController@sellerProducts')->name('users.sellerProducts');
        Route::get('user/store-reference', 'ManageUsersController@storeReference')->name('users.storeReference');
        Route::get('user/store-bonus', 'ManageUsersController@storeBonus')->name('users.storeBonus');
        Route::get('user/reference', 'ManageUsersController@reference')->name('users.reference');
        Route::get('user/pairs', 'ManageUsersController@pairs')->name('users.pairs');
        Route::get('user/product-partner-bonus', 'ManageUsersController@productPartnerBonus')->name('users.productPartnerBonus');
        Route::get('user/stockist-reference-bonus', 'ManageUsersController@stockistReferenceBonus')->name('users.stockistReferenceBonus');
        Route::get('user/stockist-bonus', 'ManageUsersController@stockistBonus')->name('users.stockistBonus');
        Route::get('user/shop-reference-bonus', 'ManageUsersController@shopReferenceBonus')->name('users.shopReferenceBonus');
        Route::get('user/e-pin-credit', 'ManageUsersController@EPinCredit')->name('users.EPinCredit');
        Route::get('user/pv', 'ManageUsersController@pv')->name('users.pv');
        Route::get('user/bv', 'ManageUsersController@bv')->name('users.bv');
        Route::get('user/promo', 'ManageUsersController@promo')->name('users.promo');
        Route::get('user/city_reference', 'ManageUsersController@city_reference')->name('users.city_reference');
        Route::get('user/franchise_bonus', 'ManageUsersController@franchise_bonus')->name('users.franchise_bonus');
        Route::get('user/franchise_ref_bonus', 'ManageUsersController@franchise_ref_bonus')->name('users.franchise_ref_bonus');
        Route::get('user/balance', 'ManageUsersController@balance')->name('users.balance');
        Route::get('user/dsp', 'ManageUsersController@dsp')->name('users.dsp');
        Route::get('user/walletStatements', 'ManageUsersController@walletStatements')->name('users.walletStatements');

        // Subscriber
        Route::get('subscriber', 'SubscriberController@index')->name('subscriber.index');
        Route::get('subscriber/send-email', 'SubscriberController@sendEmailForm')->name('subscriber.sendEmail');
        Route::post('subscriber/remove', 'SubscriberController@remove')->name('subscriber.remove');
        Route::post('subscriber/send-email', 'SubscriberController@sendEmail')->name('subscriber.sendEmail');


        // Deposit Gateway
        Route::name('gateway.')->prefix('gateway')->group(function(){
            // Automatic Gateway
            Route::get('automatic', 'GatewayController@index')->name('automatic.index');
            Route::get('automatic/edit/{alias}', 'GatewayController@edit')->name('automatic.edit');
            Route::post('automatic/update/{code}', 'GatewayController@update')->name('automatic.update');
            Route::post('automatic/remove/{code}', 'GatewayController@remove')->name('automatic.remove');
            Route::post('automatic/activate', 'GatewayController@activate')->name('automatic.activate');
            Route::post('automatic/deactivate', 'GatewayController@deactivate')->name('automatic.deactivate');


            // Manual Methods
            Route::get('manual', 'ManualGatewayController@index')->name('manual.index');
            Route::get('manual/new', 'ManualGatewayController@create')->name('manual.create');
            Route::post('manual/new', 'ManualGatewayController@store')->name('manual.store');
            Route::get('manual/edit/{alias}', 'ManualGatewayController@edit')->name('manual.edit');
            Route::post('manual/update/{id}', 'ManualGatewayController@update')->name('manual.update');
            Route::post('manual/activate', 'ManualGatewayController@activate')->name('manual.activate');
            Route::post('manual/deactivate', 'ManualGatewayController@deactivate')->name('manual.deactivate');
        });


        // DEPOSIT SYSTEM
        Route::name('deposit.')->prefix('deposit')->group(function(){
            Route::get('/', 'DepositController@deposit')->name('list');
            Route::get('pending', 'DepositController@pending')->name('pending');
            Route::get('rejected', 'DepositController@rejected')->name('rejected');
            Route::get('approved', 'DepositController@approved')->name('approved');
            Route::get('successful', 'DepositController@successful')->name('successful');
            Route::get('details/{id}', 'DepositController@details')->name('details');

            Route::post('reject', 'DepositController@reject')->name('reject');
            Route::post('approve', 'DepositController@approve')->name('approve');
            Route::get('via/{method}/{type?}', 'DepositController@depositViaMethod')->name('method');
            Route::get('/{scope}/search', 'DepositController@search')->name('search');
            Route::get('date-search/{scope}', 'DepositController@dateSearch')->name('dateSearch');

        });


        // WITHDRAW SYSTEM
        Route::name('withdraw.')->prefix('withdraw')->group(function(){
            Route::get('pending', 'WithdrawalController@pending')->name('pending');
            Route::get('approved', 'WithdrawalController@approved')->name('approved');
            Route::get('rejected', 'WithdrawalController@rejected')->name('rejected');
            Route::get('log', 'WithdrawalController@log')->name('log');
            Route::get('via/{method_id}/{type?}', 'WithdrawalController@logViaMethod')->name('method');
            Route::get('{scope}/search', 'WithdrawalController@search')->name('search');
            Route::get('date-search/{scope}', 'WithdrawalController@dateSearch')->name('dateSearch');
            Route::get('details/{id}', 'WithdrawalController@details')->name('details');
            Route::post('approve', 'WithdrawalController@approve')->name('approve');
            Route::post('reject', 'WithdrawalController@reject')->name('reject');


            // Withdraw Method
            Route::get('method/', 'WithdrawMethodController@methods')->name('method.index');
            Route::get('method/create', 'WithdrawMethodController@create')->name('method.create');
            Route::post('method/create', 'WithdrawMethodController@store')->name('method.store');
            Route::get('method/edit/{id}', 'WithdrawMethodController@edit')->name('method.edit');
            Route::post('method/edit/{id}', 'WithdrawMethodController@update')->name('method.update');
            Route::post('method/activate', 'WithdrawMethodController@activate')->name('method.activate');
            Route::post('method/deactivate', 'WithdrawMethodController@deactivate')->name('method.deactivate');
        });


        //orders
        Route::name('orders.')->prefix('orders')->group(function(){
        Route::get('admin-orders','OrderController@admin_orders')->name('admin_orders');
        Route::get('seller-orders','OrderController@seller_orders')->name('seller_orders');
        Route::post('orders/status/{id}','OrderController@status')->name('status');

        });
        // Report
        Route::get('report/referral-commission', 'ReportController@refCom')->name('report.refCom');
        Route::get('report/binary-commission', 'ReportController@binary')->name('report.binaryCom');
        Route::get('report/invest', 'ReportController@invest')->name('report.invest');

        Route::get('report/bv-log', 'ReportController@bvLog')->name('report.bvLog');
        Route::get('report/bv-log/{id}', 'ReportController@singleBvLog')->name('report.single.bvLog');


        Route::get('report/partner-earnings', 'AdminController@partner_earnings')->name('report.partner_earnings');
        Route::post('report/add-partner', 'AdminController@add_partner')->name('report.add_partner');
        Route::get('report/edit-partner/{id}', 'AdminController@edit_partner')->name('report.edit_partner');
        Route::post('report/update-partner/{id}', 'AdminController@update_partner')->name('report.update_partner');
        Route::get('report/delete-partner/{id}', 'AdminController@delete_partner')->name('report.delete_partner');
       
        Route::get('report/transaction', 'ReportController@transaction')->name('report.transaction');
        Route::get('report/transaction/search', 'ReportController@transactionSearch')->name('report.transaction.search');
        Route::get('report/login/history', 'ReportController@loginHistory')->name('report.login.history');
        Route::get('report/login/ipHistory/{ip}', 'ReportController@loginIpHistory')->name('report.login.ipHistory');
        Route::get('report/email/history', 'ReportController@emailHistory')->name('report.email.history');
        Route::post('checkUsername', 'AdminController@checkUsername')->name('report.checkUsername');

        // Admin Support
        Route::get('tickets', 'SupportTicketController@tickets')->name('ticket');
        Route::get('tickets/pending', 'SupportTicketController@pendingTicket')->name('ticket.pending');
        Route::get('tickets/closed', 'SupportTicketController@closedTicket')->name('ticket.closed');
        Route::get('tickets/answered', 'SupportTicketController@answeredTicket')->name('ticket.answered');
        Route::get('tickets/view/{id}', 'SupportTicketController@ticketReply')->name('ticket.view');
        Route::post('ticket/reply/{id}', 'SupportTicketController@ticketReplySend')->name('ticket.reply');
        Route::get('ticket/download/{ticket}', 'SupportTicketController@ticketDownload')->name('ticket.download');
        Route::post('ticket/delete', 'SupportTicketController@ticketDelete')->name('ticket.delete');


        // Language Manager
        Route::get('/language', 'LanguageController@langManage')->name('language.manage');
        Route::post('/language', 'LanguageController@langStore')->name('language.manage.store');
        Route::post('/language/delete/{id}', 'LanguageController@langDel')->name('language.manage.del');
        Route::post('/language/update/{id}', 'LanguageController@langUpdate')->name('language.manage.update');
        Route::get('/language/edit/{id}', 'LanguageController@langEdit')->name('language.key');
        Route::post('/language/import', 'LanguageController@langImport')->name('language.importLang');



        Route::post('language/store/key/{id}', 'LanguageController@storeLanguageJson')->name('language.store.key');
        Route::post('language/delete/key/{id}', 'LanguageController@deleteLanguageJson')->name('language.delete.key');
        Route::post('language/update/key/{id}', 'LanguageController@updateLanguageJson')->name('language.update.key');



        // General Setting
        Route::get('general-setting', 'GeneralSettingController@index')->name('setting.index');
        Route::post('general-setting', 'GeneralSettingController@update')->name('setting.update');
        Route::get('optimize', 'GeneralSettingController@optimize')->name('setting.optimize');

        // Logo-Icon
        Route::get('setting/logo-icon', 'GeneralSettingController@logoIcon')->name('setting.logo.icon');
        Route::post('setting/logo-icon', 'GeneralSettingController@logoIconUpdate')->name('setting.logo.icon');

        //Custom CSS
        Route::get('custom-css','GeneralSettingController@customCss')->name('setting.custom.css');
        Route::post('custom-css','GeneralSettingController@customCssSubmit');


        //Cookie
        Route::get('cookie','GeneralSettingController@cookie')->name('setting.cookie');
        Route::post('cookie','GeneralSettingController@cookieSubmit');


        // Plugin
        Route::get('extensions', 'ExtensionController@index')->name('extensions.index');
        Route::post('extensions/update/{id}', 'ExtensionController@update')->name('extensions.update');
        Route::post('extensions/activate', 'ExtensionController@activate')->name('extensions.activate');
        Route::post('extensions/deactivate', 'ExtensionController@deactivate')->name('extensions.deactivate');



        // Email Setting
        Route::get('email-template/global', 'EmailTemplateController@emailTemplate')->name('email.template.global');
        Route::post('email-template/global', 'EmailTemplateController@emailTemplateUpdate')->name('email.template.global');
        Route::get('email-template/setting', 'EmailTemplateController@emailSetting')->name('email.template.setting');
        Route::post('email-template/setting', 'EmailTemplateController@emailSettingUpdate')->name('email.template.setting');
        Route::get('email-template/index', 'EmailTemplateController@index')->name('email.template.index');
        Route::get('email-template/{id}/edit', 'EmailTemplateController@edit')->name('email.template.edit');
        Route::post('email-template/{id}/update', 'EmailTemplateController@update')->name('email.template.update');
        Route::post('email-template/send-test-mail', 'EmailTemplateController@sendTestMail')->name('email.template.test.mail');


        // SMS Setting
        Route::get('sms-template/global', 'SmsTemplateController@smsTemplate')->name('sms.template.global');
        Route::post('sms-template/global', 'SmsTemplateController@smsTemplateUpdate')->name('sms.template.global');
        Route::get('sms-template/setting','SmsTemplateController@smsSetting')->name('sms.templates.setting');
        Route::post('sms-template/setting', 'SmsTemplateController@smsSettingUpdate')->name('sms.template.setting');
        Route::get('sms-template/index', 'SmsTemplateController@index')->name('sms.template.index');
        Route::get('sms-template/edit/{id}', 'SmsTemplateController@edit')->name('sms.template.edit');
        Route::post('sms-template/update/{id}', 'SmsTemplateController@update')->name('sms.template.update');
        Route::post('email-template/send-test-sms', 'SmsTemplateController@sendTestSMS')->name('sms.template.test.sms');
        
        //price list
        Route::get('price-list/index', 'PriceListController@index')->name('price-list.index');
        Route::get('price-list/create', 'PriceListController@create')->name('price-list.create');
        Route::post('price-list/store', 'PriceListController@store')->name('price-list.store');
        Route::get('price-list/edit/{id}', 'PriceListController@edit')->name('price-list.edit');
        Route::post('price-list/update/{id}', 'PriceListController@update')->name('price-list.update');
        Route::get('price-list/delete/{id}', 'PriceListController@delete')->name('price-list.delete');

        // SEO
        Route::get('seo', 'FrontendController@seoEdit')->name('seo');


        // Frontend
        Route::name('frontend.')->prefix('frontend')->group(function () {


            Route::get('templates', 'FrontendController@templates')->name('templates');
            Route::post('templates', 'FrontendController@templatesActive')->name('templates.active');


            Route::get('frontend-sections/{key}', 'FrontendController@frontendSections')->name('sections');
            Route::post('frontend-content/{key}', 'FrontendController@frontendContent')->name('sections.content');
            Route::get('frontend-element/{key}/{id?}', 'FrontendController@frontendElement')->name('sections.element');
            Route::post('remove', 'FrontendController@remove')->name('remove');

            // Page Builder
            Route::get('manage-pages', 'PageBuilderController@managePages')->name('manage.pages');
            Route::post('manage-pages', 'PageBuilderController@managePagesSave')->name('manage.pages.save');
            Route::post('manage-pages/update', 'PageBuilderController@managePagesUpdate')->name('manage.pages.update');
            Route::post('manage-pages/delete', 'PageBuilderController@managePagesDelete')->name('manage.pages.delete');
            Route::get('manage-section/{id}', 'PageBuilderController@manageSection')->name('manage.section');
            Route::post('manage-section/{id}', 'PageBuilderController@manageSectionUpdate')->name('manage.section.update');
        });
    });
});




/*
|--------------------------------------------------------------------------
| Start User Area
|--------------------------------------------------------------------------
*/


Route::name('user.')->group(function () {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
    Route::post('/login', 'Auth\LoginController@login');
    Route::get('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register')->middleware('regStatus');
    Route::post('check-mail', 'Auth\RegisterController@checkUser')->name('checkUser');

    Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::get('password/code-verify', 'Auth\ForgotPasswordController@codeVerify')->name('password.code.verify');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetCodeEmail')->name('password.email');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
    Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/verify-code', 'Auth\ForgotPasswordController@verifyCode')->name('password.verify.code');
  
  
    //find email and username
    Route::get('email/find', 'UserController@showEmailForgetForm')->name('email.find');
    Route::post('email/find', 'UserController@showEmailUsername')->name('email.username');
});

Route::name('user.')->prefix('user')->group(function () {
    Route::middleware('auth')->group(function () {
        Route::get('authorization', 'AuthorizationController@authorizeForm')->name('authorization');
        Route::get('resend-verify', 'AuthorizationController@sendVerifyCode')->name('send.verify.code');
        Route::post('verify-email', 'AuthorizationController@emailVerification')->name('verify.email');
        Route::post('verify-sms', 'AuthorizationController@smsVerification')->name('verify.sms');
        Route::post('verify-g2fa', 'AuthorizationController@g2faVerification')->name('go2fa.verify');


        Route::middleware(['checkStatus'])->group(function () {
            Route::get('dashboard', 'UserController@home')->name('home');
			
		
			
			Route::get('store-reference', 'UserController@storeReference')->name('storeReference');
			Route::get('store-bonus', 'UserController@storeBonus')->name('storeBonus');
			Route::get('reference', 'UserController@reference')->name('reference');
			Route::get('pairs', 'UserController@pairs')->name('pairs');
			Route::get('pairs-bonus', 'UserController@pairs_bonus')->name('pairs_bonus');
			Route::get('product-partner-bonus', 'UserController@productPartnerBonus')->name('productPartnerBonus');
			Route::get('stockist-reference-bonusss', 'UserController@stockistReferenceBonus')->name('stockistReferenceBonus');
			Route::get('stockist-bonus', 'UserController@stockistBonus')->name('stockistBonus');
			Route::get('shop-reference-bonus', 'UserController@shopReferenceBonus')->name('shopReferenceBonus');
			Route::get('e-pin-credit', 'UserController@EPinCredit')->name('EPinCredit');
			Route::get('pv', 'UserController@pv')->name('pv');
			Route::get('bv', 'UserController@bv')->name('bv');
			Route::get('promo', 'UserController@promo')->name('promo');
			Route::get('city_reference', 'UserController@city_reference')->name('city_reference');
			Route::get('franchise_bonus', 'UserController@franchise_bonus')->name('franchise_bonus');
			Route::get('franchise_ref_bonus', 'UserController@franchise_ref_bonus')->name('franchise_ref_bonus');
			Route::get('balance', 'UserController@balance')->name('balance');
			Route::get('deposits', 'UserController@deposit')->name('deposits');
			Route::get('total_invest', 'UserController@total_invest')->name('total_invest');
			Route::get('dsp-ref-bonus', 'UserController@dsp_ref_bonus')->name('dsp-ref-bonus');
			
			Route::get('total_withdraw', 'UserController@total_withdraw')->name('total_withdraw');
			Route::get('complete_withdraw', 'UserController@completed_withdraw')->name('complete_withdraw');
			Route::get('pending_withdraw', 'UserController@pending_withdraw')->name('pending_withdraw');
			Route::get('rejected_withdraw', 'UserController@rejected_withdraw')->name('rejected_withdraw');
			Route::get('pending_amount', 'UserController@pending_amount')->name('pending_amount');
			Route::get('company_partnership', 'UserController@company_partnership')->name('company_partnership');
			Route::get('samiExpenseBalance', 'UserController@samiExpenseBalance')->name('samiExpenseBalance');
			//Route::get('user/walletStatements', 'ManageUsersController@walletStatements')->name('users.walletStatements');


            Route::get('profile-setting', 'UserController@profile')->name('profile.setting');
            Route::post('profile-setting', 'UserController@submitProfile');
            Route::get('change-password', 'UserController@changePassword')->name('change.password');
            Route::post('change-password', 'UserController@submitPassword');
            
            Route::get('my-records', 'MyRecordsContorller@index')->name('my.records');
            Route::post('my-records', 'MyRecordsContorller@index')->name('my.records');

            //plan
            Route::get('/plan', 'PlanController@planIndex')->name('plan.index');
            Route::get('/price-list', 'UserController@price_list')->name('price-list');
            Route::post('/plan', 'PlanController@planStore')->name('plan.purchase');
            Route::post('/dspplan', 'PlanController@planDSPStore')->name('dspplan.purchase');
            Route::get('/referral-log', 'UserController@referralCom')->name('referral.log');
            Route::get('/binary-log', 'PlanController@binaryCom')->name('binary.log');
            Route::get('/binary-summery', 'PlanController@binarySummery')->name('binary.summery');
            Route::get('/bv-log', 'PlanController@bvlog')->name('bv.log');
            Route::get('/referrals', 'PlanController@myRefLog')->name('my.ref');
            Route::get('/tree', 'PlanController@myTree')->name('my.tree');
            Route::get('/tree/{user}', 'PlanController@otherTree')->name('other.tree');
            Route::get('/tree/search', 'PlanController@otherTree')->name('other.tree.search');

            //stockist
            Route::get('/stockist', 'PlanController@stockistIndex')->name('stockist.index');
           	Route::post('/purchase_stock', 'PlanController@stockistStore')->name('purchase_stock');
			Route::post('/stockist', 'PlanController@stockistStore2')->name('stockist.purchase');
            Route::get('/stockistlist', 'PlanController@stockistlist')->name('stockistlist');
			Route::post('/pinPassword','PlanController@pinPassword')->name('pinPassword');
			Route::post('/stockistPurchaseFromDash', 'PlanController@stockistPurchaseFromDash')->name('stockist.stockistPurchaseFromDash');
			
			 //Member ship 
            Route::get('/membership', 'PlanController@membership')->name('membership');
            Route::POST('/membership', 'PlanController@memberStore')->name('membership.purchase');
            
            Route::get('/shops-franchises', 'UserController@shops_franchises')->name('shops-franchises');
            //balance transfer
             Route::get('/transfer', 'UserController@indexTransfer')->name('balance.transfer');
             Route::post('/transfer', 'UserController@balanceTransfer')->name('balance.transfer.post');
            Route::post('/search-user', 'UserController@searchUser')->name('search.user');

            //Report
            Route::get('report/deposit/log', 'UserReportController@depositHistory')->name('report.deposit');
            Route::get('report/invest/log', 'UserReportController@investLog')->name('report.invest');
            Route::get('report/transactions/log', 'UserReportController@transactions')->name('report.transactions');
            Route::get('report/withdraw/log', 'UserReportController@withdrawLog')->name('report.withdraw');
            Route::get('report/referral/commission', 'UserReportController@refCom')->name('report.refCom');
            Route::get('report/binary/commission', 'UserReportController@binaryCom')->name('report.binaryCom');


            //2FA
            Route::get('twofactor', 'UserController@show2faForm')->name('twofactor');
            Route::post('twofactor/enable', 'UserController@create2fa')->name('twofactor.enable');
            Route::post('twofactor/disable', 'UserController@disable2fa')->name('twofactor.disable');


            // Deposit
            Route::any('/deposit', 'Gateway\PaymentController@deposit')->name('deposit');
            Route::post('deposit/insert', 'Gateway\PaymentController@depositInsert')->name('deposit.insert');
            Route::get('deposit/preview', 'Gateway\PaymentController@depositPreview')->name('deposit.preview');
            Route::get('deposit/confirm', 'Gateway\PaymentController@depositConfirm')->name('deposit.confirm');
            Route::get('deposit/manual', 'Gateway\PaymentController@manualDepositConfirm')->name('deposit.manual.confirm');
            Route::post('deposit/manual', 'Gateway\PaymentController@manualDepositUpdate')->name('deposit.manual.update');
            Route::any('deposit/history', 'UserController@depositHistory')->name('deposit.history');

            // Withdraw
            Route::get('/withdraw', 'UserController@withdrawMoney')->name('withdraw');
            Route::post('/withdraw', 'UserController@withdrawStore')->name('withdraw.money');
            Route::get('/withdraw/preview', 'UserController@withdrawPreview')->name('withdraw.preview');
            Route::post('/withdraw/preview', 'UserController@withdrawSubmit')->name('withdraw.submit');
            Route::get('/withdraw/history', 'UserController@withdrawLog')->name('withdraw.history');

            //Orders
            Route::get('orders','UserController@orders')->name('orders');
			Route::get('rewards','UserController@display_rewards')->name('rewards');

			Route::get('lucky-draw','UserController@lucky_draw')->name('lucky-draw');
			Route::get('lucky-draw-myorders','UserController@lucky_draw_myorders')->name('lucky-draw-myorders');
            //purchase
            Route::post('purchase','UserController@purchase')->name('purchase');
        });
    });
});

Route::post('/check/referral', 'SiteController@CheckUsername')->name('check.referral');
Route::post('/get/user/position', 'SiteController@userPosition')->name('get.user.position');
Route::post('/get/sponsor/position', 'SiteController@sponsorPosition')->name('get.sponsor.position');

Route::get('/contact', 'SiteController@contact')->name('contact');
Route::post('/contact', 'SiteController@contactSubmit');
Route::get('/change/{lang?}', 'SiteController@changeLanguage')->name('lang');

Route::get('/cookie/accept', 'SiteController@cookieAccept')->name('cookie.accept');

Route::get('blog/{id}/{slug}', 'SiteController@blogDetails')->name('blog.details');

Route::get('placeholder-image/{size}', 'SiteController@placeholderImage')->name('placeholder.image');

Route::get('/products/{catId?}', 'SiteController@products')->name('products');
Route::get('/product/{id}/{slug}', 'SiteController@productDetails')->name('product.details');

Route::get('/blog', 'SiteController@blog')->name('blog');
Route::get('/blog/details/{slug}/{id}', 'SiteController@blogDeatail')->name('blog.details');
Route::get('policy/{slug}/{id}', 'SiteController@policy')->name('policy');

Route::get('/{slug}', 'SiteController@pages')->name('pages');
Route::get('/', 'SiteController@index')->name('home');