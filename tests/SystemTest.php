<?php

namespace Jahuty;

class SystemTest extends \PHPUnit\Framework\TestCase
{
    private $jahuty;

    public function setUp(): void
    {
        $this->jahuty = new Client(
            'kn2Kj5ijmT2pH6ZKqAQyNexUqKeRM4VG6DDgWN1lIcc'
        );
    }

    public function testAllRenders(): void
    {
        $renders = $this->jahuty->snippets->allRenders('test', [
            'params' => [
                '*' => ['foo' => 'foo'],
                62  => ['bar' => 'bar']
            ]
        ]);

        $this->assertContainsOnlyInstancesOf(Resource\Render::class, $renders);

        // Rendering a snippet in the collection using the _same_ parameters
        // passed into the collection should use the cached value.
        $params = ['foo' => 'foo'];
        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(1, ['params' => $params]);
        $end    = microtime(true);

        $maximum = 1;
        $actual  = ($end - $start) * 1000;

        $this->assertLessThan($maximum, $actual);

        $params = ['foo' => 'foo', 'bar' => 'bar'];
        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(62, ['params' => $params]);
        $end    = microtime(true);

        $maximum = 1;
        $actual  = ($end - $start) * 1000;

        $this->assertLessThan($maximum, $actual);

        // Rendering a snippet in the collection using _different_ parameters
        // should not use the cached value.
        $params = ['foo' => 'foo', 'bar' => 'bar', 'baz' => 'baz'];
        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(62, ['params' => $params]);
        $end    = microtime(true);

        $minimum = 10;
        $actual  = ($end - $start) * 1000;

        $this->assertGreaterThan($minimum, $actual);
    }

    public function testAllRendersWithLatest(): void
    {
        $renders = $this->jahuty->snippets->allRenders('test', [
            'prefer_latest_content' => true
        ]);

        $last = end($renders);

        $this->assertEquals(
            '<p>This content is latest.</p>',
            $last->getContent()
        );
    }

    public function testRenderWithoutParameters(): void
    {
        $render = $this->jahuty->snippets->render(1);

        $this->assertEquals(
            '<p>This is my first snippet!</p>',
            $render->getContent()
        );

        // The second request hits the cache.
        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(1);
        $end    = microtime(true);

        // The actual time should less than a maximum number of milliseconds.
        $maximum = 1;
        $actual  = ($end - $start) * 1000;

        $this->assertLessThan($maximum, $actual);
    }

    public function testRenderWithParameters(): void
    {
        $params = ['foo' => 'foo', 'bar' => 'bar'];

        $render = $this->jahuty->snippets->render(62, ['params' => $params]);

        $this->assertEquals('<p>This foo is bar.</p>', $render->getContent());

        // The second request should hit the cache.
        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(62, ['params' => $params]);
        $end    = microtime(true);

        $maximum = 1;
        $actual  = ($end - $start) * 1000;

        $this->assertLessThan($maximum, $actual);

        // Change the params and the third render should use the network.
        $params['baz'] = 'baz';

        $start  = microtime(true);
        $render = $this->jahuty->snippets->render(62, ['params' => $params]);
        $end    = microtime(true);

        $minimum = 10;
        $actual  = ($end - $start) * 1000;

        $this->assertGreaterThan($minimum, $actual);
    }

    public function testRenderWithLatest(): void
    {
        $render = $this->jahuty->snippets->render(102, [
            'prefer_latest_content' => true
        ]);

        $this->assertEquals(
            '<p>This content is latest.</p>',
            $render->getContent()
        );
    }

    public function testProblem(): void
    {
        $this->expectException(Exception\Error::class);

        // The API key doesn't have access to this snippet.
        $this->jahuty->snippets->render(999);
    }
}
