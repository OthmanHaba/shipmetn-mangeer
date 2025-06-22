<?php

return [
    // General
    'dashboard' => 'لوحة التحكم',
    'create' => 'إنشاء',
    'edit' => 'تعديل',
    'delete' => 'حذف',
    'save' => 'حفظ',
    'cancel' => 'إلغاء',
    'submit' => 'إرسال',
    'back' => 'رجوع',
    'actions' => 'إجراءات',
    'details' => 'تفاصيل',
    'status' => 'الحالة',
    'date' => 'التاريخ',

    // Navigation
    'navigation' => [
        'shipments' => 'الشحنات',
        'customers' => 'العملاء',
        'nodes' => 'نقاط الخدمات اللوجستية',
        'accounting' => 'المحاسبة',
        'invoices' => 'الفواتير',
        'expenses' => 'المصاريف',
        'users' => 'المستخدمين',
        'roles' => 'الأدوار',
        'chart_of_accounts' => 'دليل الحسابات',
    ],

    // Shipments
    'shipment' => [
        'title' => 'شحنة',
        'title_plural' => 'الشحنات',
        'reference_id' => 'الرقم المرجعي',
        'status' => 'الحالة',
        'shipping_mode' => 'وسيلة الشحن',
        'sea' => 'بحري',
        'air' => 'جوي',
        'land' => 'بري',
        'shipper' => 'المرسل',
        'consignee' => 'المستلم',
        'origin' => 'المصدر',
        'destination' => 'الوجهة',
        'created_at' => 'تاريخ الإنشاء',
        'estimated_departure' => 'تاريخ المغادرة المتوقع',
        'estimated_arrival' => 'تاريخ الوصول المتوقع',
        'actual_departure' => 'تاريخ المغادرة الفعلي',
        'actual_arrival' => 'تاريخ الوصول الفعلي',
        'created_by' => 'تم الإنشاء بواسطة',
        'change_status' => 'تغيير الحالة',
        'change_status_tooltip' => 'تغيير حالة الشحنة',
        'status_updated' => 'تم تحديث الحالة',
        'status_changed_to' => 'تم تغيير حالة الشحنة إلى',
        'statuses' => [
            'pending' => 'قيد الانتظار',
            'in_transit' => 'قيد النقل',
            'at_warehouse' => 'في المستودع',
            'delivered' => 'تم التسليم',
            'cancelled' => 'تم الإلغاء',
        ],
    ],

    // Shipment Legs
    'shipment_leg' => [
        'title' => 'مرحلة الشحن',
        'title_plural' => 'مراحل الشحن',
        'sequence_order' => 'ترتيب التسلسل',
        'from_node' => 'من نقطة',
        'to_node' => 'إلى نقطة',
        'status' => 'الحالة',
        'departure_timestamp' => 'وقت المغادرة',
        'arrival_timestamp' => 'وقت الوصول',
        'statuses' => [
            'pending' => 'قيد الانتظار',
            'in_progress' => 'قيد التنفيذ',
            'completed' => 'مكتمل',
        ],
    ],

    // Customers
    'customer' => [
        'title' => 'عميل',
        'title_plural' => 'العملاء',
        'name' => 'الاسم',
        'contact_person' => 'الشخص المسؤول',
        'email' => 'البريد الإلكتروني',
        'phone_number' => 'رقم الهاتف',
        'address' => 'العنوان',
    ],

    // Nodes
    'node' => [
        'title' => 'نقطة',
        'title_plural' => 'النقاط',
        'name' => 'الاسم',
        'type' => 'النوع',
        'address' => 'العنوان',
        'city' => 'المدينة',
        'country' => 'البلد',
        'types' => [
            'warehouse' => 'مستودع',
            'customer_address' => 'عنوان العميل',
            'port' => 'ميناء',
            'airport' => 'مطار',
            'land_depot' => 'محطة برية',
        ],
    ],

    // Accounting
    'chart_of_account' => [
        'title' => 'حساب',
        'title_plural' => 'دليل الحسابات',
        'account_number' => 'رقم الحساب',
        'account_name' => 'اسم الحساب',
        'account_type' => 'نوع الحساب',
        'parent_account' => 'الحساب الأب',
        'types' => [
            'asset' => 'أصول',
            'liability' => 'التزامات',
            'equity' => 'حقوق الملكية',
            'revenue' => 'إيرادات',
            'expense' => 'مصاريف',
        ],
    ],

    // Journal Entries
    'journal_entry' => [
        'title' => 'قيد يومية',
        'title_plural' => 'قيود اليومية',
        'entry_date' => 'تاريخ القيد',
        'description' => 'الوصف',
        'reference_type' => 'نوع المرجع',
        'reference_id' => 'رقم المرجع',
        'shipment' => 'الشحنة المرتبطة',
        'created_by' => 'تم الإنشاء بواسطة',
    ],

    // Journal Lines
    'journal_line' => [
        'title' => 'بند قيد',
        'title_plural' => 'بنود القيود',
        'account' => 'الحساب',
        'entry_type' => 'نوع القيد',
        'amount' => 'المبلغ',
        'types' => [
            'debit' => 'مدين',
            'credit' => 'دائن',
        ],
    ],

    // Invoices
    'invoice' => [
        'title' => 'فاتورة',
        'title_plural' => 'الفواتير',
        'invoice_number' => 'رقم الفاتورة',
        'shipment' => 'الشحنة المرتبطة',
        'customer' => 'العميل',
        'issue_date' => 'تاريخ الإصدار',
        'due_date' => 'تاريخ الاستحقاق',
        'total_amount' => 'المبلغ الإجمالي',
        'status' => 'الحالة',
        'generate_invoice' => 'إنشاء فاتورة',
        'generate_invoice_tooltip' => 'إنشاء فاتورة لهذه الشحنة',
        'statuses' => [
            'draft' => 'مسودة',
            'sent' => 'تم الإرسال',
            'paid' => 'تم الدفع',
            'void' => 'ملغى',
        ],
    ],

    // Expenses
    'expense' => [
        'title' => 'مصروف',
        'title_plural' => 'المصاريف',
        'shipment' => 'الشحنة المرتبطة',
        'shipment_leg' => 'مرحلة الشحن المرتبطة',
        'vendor_name' => 'اسم المورد',
        'expense_date' => 'تاريخ المصروف',
        'amount' => 'المبلغ',
        'description' => 'الوصف',
        'account' => 'الحساب',
    ],

    // Users
    'user' => [
        'title' => 'مستخدم',
        'title_plural' => 'المستخدمين',
        'full_name' => 'الاسم الكامل',
        'email' => 'البريد الإلكتروني',
        'password' => 'كلمة المرور',
        'role' => 'الدور',
        'active' => 'نشط',
    ],

    // Roles
    'role' => [
        'title' => 'دور',
        'title_plural' => 'الأدوار',
        'name' => 'الاسم',
    ],

    // Widgets
    'widgets' => [
        'stats_overview' => [
            'total_shipments' => 'إجمالي الشحنات',
            'total_shipments_description' => 'جميع الشحنات في النظام',
            'active_customers' => 'العملاء النشطين',
            'active_customers_description' => 'إجمالي العملاء المسجلين',
            'revenue' => 'الإيرادات',
            'revenue_description' => 'إجمالي المبلغ المفوتر',
            'expenses' => 'المصاريف',
            'expenses_description' => 'إجمالي المصاريف',
        ],
        'financial_overview' => 'نظرة عامة مالية',
        'shipments_by_type' => 'الشحنات حسب النوع',
        'latest_shipments' => 'أحدث الشحنات',
        'created' => 'تم الإنشاء',
        'view' => 'عرض',
        'view_legs' => 'عرض المراحل',
        'view_shipment_legs' => 'عرض مراحل الشحن',
        'chart_labels' => [
            'revenue' => 'الإيرادات',
            'expenses' => 'المصاريف',
            'shipments_by_type' => 'الشحنات حسب النوع',
        ],
    ],

    // Common UI elements
    'ui' => [
        'search' => 'البحث',
        'filter' => 'تصفية',
        'export' => 'تصدير',
        'import' => 'استيراد',
        'bulk_actions' => 'إجراءات مجمعة',
        'no_records_found' => 'لم يتم العثور على سجلات',
        'loading' => 'جارٍ التحميل...',
        'confirm' => 'تأكيد',
        'yes' => 'نعم',
        'no' => 'لا',
        'close' => 'إغلاق',
        'select_option' => 'اختر خياراً',
        'required_field' => 'هذا الحقل مطلوب',
    ],
];
