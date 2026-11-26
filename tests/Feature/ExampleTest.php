<?php

declare(strict_types=1);

test('the application returns a successful response')->get('http://3pontos.test/')->assertOk();
