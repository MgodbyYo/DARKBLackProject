<?php

namespace Tests\Feature;

use App\Forecast;
use App\Site;
use Carbon\Carbon;
use Tests\TestCase;

class ForecastTest extends TestCase
{
    /** @test */
    public function it_exists()
    {
        $this->assertClassHasAttribute('site', 'app\Forecast');
        $this->assertClassHasAttribute('date', 'app\Forecast');
        $this->assertClassHasAttribute('growth_rate', 'app\Forecast');
        $this->assertClassHasAttribute('seven_day', 'app\Forecast');
    }

    /** @test */
    public function provides_seven_day_forecast()
    {
        // Setup some Sales Data
        // The Site will provide all the necessary Sales Data
        $site = new Site();

        $forecast = new Forecast($site);

        // You will have to write the mostRecent28DayAverage method
        // The default is 0% growth, so the forecast should be the same as the 28 day average
        $this->assertEquals($site->mostRecent28DayAverage(), $forecast->seven_day);
    }

    /** @test */
    public function can_set_growth_rate()
    {
        $site = new Site();

        $forecast = new Forecast($site);

        // The forecast should default to a zero growth rate
        $this->assertEquals(0, $forecast->growth());

        // We should be able to set the growth rate
        // Try out a few valid inputs
        // Growth is percentage increase / decrease each week

        $forecast->growth(1);
        $this->assertEquals(1, $forecast->growth());

        $forecast->growth(-1);
        $this->assertEquals(-1, $forecast->growth());

        $forecast->growth(1.5);
        $this->assertEquals(1.5, $forecast->growth());

        $forecast->growth(-1.5);
        $this->assertEquals(-1.5, $forecast->growth());
    }

    /** @test */
    public function growth_rate_determines_forecast()
    {
        // Setup some Sales Data
        // The Site will provide all the necessary Sales Data
        $site = new Site();

        $forecast = new Forecast($site);

        $twenty_eight_day_sales_average = $site->mostRecent28DayAverage();

        // The default is 0% growth
        $this->assertEquals($twenty_eight_day_sales_average, $forecast->seven_day);

        // Test a bunch of growth rates
        $growth_rates = [5.25, 4.75, 1.1, 0.1, -0.1, -1.24, -4.32];

        foreach ($growth_rates as $growth_rate) {
            $forecast->growth($growth_rate);
            $this->assertEquals($twenty_eight_day_sales_average * (1 + $growth_rate / 100), $forecast->seven_day);
        }
    }

    /** @test */
    public function can_set_date()
    {
        // The forecast will default to calculate using the most recent data
        // However, we want the option of determining what a previous forecast was

        $site = new Site();

        $forecast = new Forecast($site);

        $forecast->date('2018-01-01');

        $this->assertEquals('2018-01-01', $forecast->date());
    }

    /** @test */
    public function can_save_and_load_settings()
    {
        $site = new Site();

        $forecast = new Forecast($site);

        $growth_rate = 1.23;

        $date = '2018-01-01';

        // Set and save the forecast settings

        $forecast->growth($growth_rate);

        $forecast->date($date);

        $forecast->save();

        // Those values should be saved in the database
        $this->assertDatabaseHas('sales', [
            'site_id'       => $site->id,
            'date'          => $date,
            'forecast_rate' => $growth_rate
        ]);

        // Load the settings

        // You may optionally pass in a date to the forecast, which will look for settings on that date
        $forecast = new Forecast($site, $date);

        $this->assertEquals($date, $forecast->date());
        $this->assertEquals($growth_rate, $forecast->growth());
    }

    /** @test */
    public function forecast_from_specific_date()
    {
        // We want to be able to specify a past date and determine the forecast 7 days after that

        $site = new Site();

        $forecast = new Forecast($site);

        $five_days_ago = Carbon::now()->subDay(5)->toDateString();

        $forecast->date($five_days_ago);

        // You will have to write this method, let the site provide it's sales data on a specific date
        // Expect this to return a Sale model
        $sales = $site->salesOn($five_days_ago);

        // default is 0% growth
        $this->assertEquals($sales->twenty_eight_day_average, $forecast->seven_day);

        // Change the rate and recalculate
        $growth_rate = 1.23;
        $forecast->growth($growth_rate);
        $this->assertEquals($sales->twenty_eight_day_average * (1 + $growth_rate / 100), $forecast->seven_day);
    }
}
