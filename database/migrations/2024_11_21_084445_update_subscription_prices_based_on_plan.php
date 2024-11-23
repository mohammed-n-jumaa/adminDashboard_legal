<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateSubscriptionPricesBasedOnPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // تحديث السعر بناءً على الخطة
        DB::table('subscriptions')
            ->update([
                'price' => DB::raw("
                    CASE
                        WHEN plan = 'monthly' THEN 3
                        WHEN plan = 'semiannual' THEN 15
                        WHEN plan = 'annual' THEN 28
                        ELSE 0
                    END
                ")
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // إرجاع السعر إلى القيمة الافتراضية (0.00)
        DB::table('subscriptions')->update(['price' => 0]);
    }
}
