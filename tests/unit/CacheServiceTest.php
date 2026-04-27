<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use App\Services\CacheService;

/**
 * CacheServiceTest
 * 
 * Valida que la interfaz y el patrón Remember del servicio funcionen.
 */
class CacheServiceTest extends CIUnitTestCase
{
    public function testSetAndGetBasicTypes()
    {
        $cache = new CacheService();
        $cache->set('test_key_1', 'ValiosoDato', 5);

        $value = $cache->get('test_key_1');
        $this->assertEquals('ValiosoDato', $value);
    }

    public function testRememberPatternReturnsStoredData()
    {
        $cache = new CacheService();
        
        $executionCount = 0;
        
        // La primera debería ejecutar la función (+1)
        $data1 = $cache->remember('closure_test', 10, function() use (&$executionCount) {
             $executionCount++;
             return "Data Generada Cara de DB";
        });

        // La segunda vez NO debe ejecutar la función (Retorna del cache original de la línea anterior)
        $data2 = $cache->remember('closure_test', 10, function() use (&$executionCount) {
             $executionCount++;
             return "Fake Override Dummie";
        });

        $this->assertEquals("Data Generada Cara de DB", $data1);
        $this->assertEquals("Data Generada Cara de DB", $data2);
                
        // El callback Closure sólo debió dispararse UNA VEZ en todo el Request.
        $this->assertEquals(1, $executionCount);
    }

    public function testDeleteInvalidatesKey()
    {
        $cache = new CacheService();
        $cache->set('to_delete', 500, 10);
        $this->assertEquals(500, $cache->get('to_delete'));

        $cache->delete('to_delete');
        $this->assertNull($cache->get('to_delete'));
    }
}
