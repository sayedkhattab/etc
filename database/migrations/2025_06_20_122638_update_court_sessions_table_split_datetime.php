<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('court_sessions', function (Blueprint $table) {
            // إضافة الأعمدة الجديدة
            $table->date('date')->after('description')->nullable();
            $table->time('time')->after('date')->nullable();
            
            // تغيير اسم عمود status_id إلى session_status_id
            $table->renameColumn('status_id', 'session_status_id');
        });
        
        // نقل البيانات من date_time إلى date و time
        $sessions = DB::table('court_sessions')->get();
        foreach ($sessions as $session) {
            if ($session->date_time) {
                $dateTime = new DateTime($session->date_time);
                DB::table('court_sessions')
                    ->where('id', $session->id)
                    ->update([
                        'date' => $dateTime->format('Y-m-d'),
                        'time' => $dateTime->format('H:i:s')
                    ]);
            }
        }
        
        // حذف العمود القديم
        Schema::table('court_sessions', function (Blueprint $table) {
            $table->dropColumn('date_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('court_sessions', function (Blueprint $table) {
            // إضافة العمود القديم
            $table->dateTime('date_time')->after('description')->nullable();
            
            // تغيير اسم العمود مرة أخرى
            $table->renameColumn('session_status_id', 'status_id');
        });
        
        // نقل البيانات من date و time إلى date_time
        $sessions = DB::table('court_sessions')->get();
        foreach ($sessions as $session) {
            if ($session->date && $session->time) {
                $dateTime = $session->date . ' ' . $session->time;
                DB::table('court_sessions')
                    ->where('id', $session->id)
                    ->update(['date_time' => $dateTime]);
            }
        }
        
        // حذف الأعمدة الجديدة
        Schema::table('court_sessions', function (Blueprint $table) {
            $table->dropColumn(['date', 'time']);
        });
    }
};
