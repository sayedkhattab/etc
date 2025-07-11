# دليل نقل البيانات - حذف بيانات جدول الطلبات

## الخيارات المتاحة

### 1. حذف بيانات جدول الطلبات فقط (الأسهل والأسرع)

```bash
php artisan orders:clear
```

هذا الأمر سيحذف جميع البيانات من جدول الطلبات فقط، مع الحفاظ على جميع الجداول الأخرى.

**الخيارات:**
- `--confirm`: تخطي رسالة التأكيد

```bash
php artisan orders:clear --confirm
```

### 2. نقل البيانات الآمن (مع نسخ احتياطية)

```bash
php artisan data:transfer-safely
```

هذا الأمر سينشئ نسخ احتياطية من جميع الجداول، ثم يحذف بيانات جدول الطلبات فقط.

**الخيارات:**
- `--backup`: إنشاء نسخة احتياطية قبل النقل
- `--confirm`: تخطي رسالة التأكيد

```bash
php artisan data:transfer-safely --backup --confirm
```

### 3. استخدام ملف الهجرة

```bash
php artisan migrate
```

سيقوم بتشغيل ملف الهجرة `2025_07_11_000000_clear_orders_table.php` الذي يحذف بيانات جدول الطلبات.

## التحذيرات المهمة

⚠️ **تحذير**: هذه العمليات لا يمكن التراجع عنها. تأكد من إنشاء نسخة احتياطية قبل التنفيذ.

### إنشاء نسخة احتياطية يدوياً

```bash
# إنشاء نسخة احتياطية من قاعدة البيانات
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql

# أو باستخدام phpMyAdmin
# 1. افتح phpMyAdmin
# 2. اختر قاعدة البيانات
# 3. اذهب إلى تبويب Export
# 4. اختر Custom
# 5. اضغط Go
```

## ما يحدث في كل عملية

### orders:clear
- ✅ يحذف جميع البيانات من جدول `orders`
- ✅ يحافظ على هيكل الجدول
- ✅ لا يؤثر على أي جدول آخر
- ✅ سريع وآمن

### data:transfer-safely
- ✅ ينشئ نسخ احتياطية من جميع الجداول
- ✅ يحذف بيانات جدول `orders` فقط
- ✅ يستعيد جميع البيانات الأخرى
- ✅ ينظف الجداول المؤقتة
- ⚠️ يستغرق وقتاً أطول

### ملف الهجرة
- ✅ يحذف بيانات جدول `orders`
- ✅ يمكن تتبعه في سجل الهجرات
- ✅ يمكن التراجع عنه (لكن البيانات لن تعود)

## التوصيات

1. **للبيئة التطويرية**: استخدم `php artisan orders:clear`
2. **للبيئة الإنتاجية**: استخدم `php artisan data:transfer-safely --backup`
3. **للنشر التلقائي**: استخدم ملف الهجرة

## استكشاف الأخطاء

### إذا حدث خطأ في الاتصال بقاعدة البيانات
```bash
php artisan config:clear
php artisan cache:clear
```

### إذا لم يتم العثور على الأوامر
```bash
php artisan config:clear
composer dump-autoload
```

### للتحقق من حالة قاعدة البيانات
```bash
php artisan migrate:status
```

## ملاحظات إضافية

- جميع الأوامر تتطلب اتصال صحيح بقاعدة البيانات
- تأكد من أن المستخدم لديه صلاحيات كافية
- في بيئة الإنتاج، يُنصح بإنشاء نسخة احتياطية كاملة
- يمكن تشغيل الأوامر عدة مرات بأمان 