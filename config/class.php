<?php
# 类的映射
return [
    # 缓存类
    'Cache'=>\Service\Cache::class,
    # 数据库类
    'DB'=>\Service\DB::class,
    # Hash
    'Hash'=>\Service\Hash::class,
    # 配置类
    'Config'=>\Kernel\Config::class,
    # url 处理类
    'Url'=>\Service\Url::class,
    # Http 类
    'Http'=>\Service\Http::class,
];