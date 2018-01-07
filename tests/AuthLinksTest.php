<?php

namespace Rigorbb\FastAuthLinks\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Carbon;
use Orchestra\Testbench\TestCase;
use Rigorbb\FastAuthLinks\FastAuthLinkFacade;
use Rigorbb\FastAuthLinks\FastAuthLinkProvider;

class AuthLinksTest extends TestCase {

    public function setUp()
    {
        parent::setUp();
        $this->user = new User();
    }

    /**
     * @test
     */
    function link_must_be_valid_one_hour()
    {
        $link  = auth_link_hourly('http://link.test', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link));

        $link1  = auth_link_hourly('http://link.test?param=fast', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link1));

        Carbon::setTestNow(Carbon::now()->addHour(2));

        $this->assertFalse(FastAuthLinkFacade::checkLink($link));
        $this->assertFalse(FastAuthLinkFacade::checkLink($link1));
    }

    /**
     * @test
     */
    function link_must_be_valid_one_day()
    {
        $link  = auth_link_daily('http://link.test', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link));

        $link1  = auth_link_daily('http://link.test?param=fast', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link1));

        Carbon::setTestNow(Carbon::now()->addDay(2));

        $this->assertFalse(FastAuthLinkFacade::checkLink($link));
        $this->assertFalse(FastAuthLinkFacade::checkLink($link1));
    }

    /**
     * @test
     */
    function link_must_be_valid_one_month()
    {
        $link  = auth_link_daily('http://link.test', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link));

        $link1  = auth_link_daily('http://link.test?param=fast', $this->user);
        $this->assertTrue(FastAuthLinkFacade::checkLink($link1));

        Carbon::setTestNow(Carbon::now()->addMonth(2));

        $this->assertFalse(FastAuthLinkFacade::checkLink($link));
        $this->assertFalse(FastAuthLinkFacade::checkLink($link1));
    }

    protected function getPackageProviders($app)
    {
        return [FastAuthLinkProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'FastAuthLink' => FastAuthLinkFacade::class
        ];
    }
}