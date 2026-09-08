import { describe, expect, it, vi } from 'vitest';
import { CacheService } from '@/workless/infrastructure/cache/cache.service';

describe('CacheService', () => {
  it('stores JSON data and deletes a namespace by prefix', async () => {
    const cache = new CacheService(null);

    await cache.set('platform:companies:1', { id: 1, name: 'One' }, 60);
    await cache.set('platform:companies:2', { id: 2, name: 'Two' }, 60);
    await cache.set('platform:users:1', { id: 1 }, 60);
    await cache.delByPrefix('platform:companies:');

    await expect(cache.get('platform:companies:1')).resolves.toBeNull();
    await expect(cache.get('platform:companies:2')).resolves.toBeNull();
    await expect(cache.get('platform:users:1')).resolves.toEqual({ id: 1 });
  });

  it('coalesces concurrent cache misses for the same key', async () => {
    const cache = new CacheService(null);
    const resolver = vi.fn(async () => ({ id: 1 }));

    const values = await Promise.all([
      cache.remember('platform:companies:1', 60, resolver),
      cache.remember('platform:companies:1', 60, resolver),
      cache.remember('platform:companies:1', 60, resolver),
    ]);

    expect(values).toEqual([{ id: 1 }, { id: 1 }, { id: 1 }]);
    expect(resolver).toHaveBeenCalledTimes(1);
  });

  it('rejects HTML, empty keys, and invalid TTL values', async () => {
    const cache = new CacheService(null);

    await expect(cache.set('page', '<!doctype html><html></html>', 60)).rejects.toThrow(
      'HTML documents cannot be stored',
    );
    await expect(
      cache.set('page', { payload: { content: '<main>Rendered page</main>' } }, 60),
    ).rejects.toThrow('HTML documents cannot be stored');
    await expect(cache.set('', { id: 1 }, 60)).rejects.toThrow('Cache key cannot be empty');
    await expect(cache.set('platform:companies:1', { id: 1 }, 0)).rejects.toThrow(
      'Cache TTL must be a positive integer',
    );
  });

  it('expires cached data after its TTL', async () => {
    vi.useFakeTimers();
    try {
      const cache = new CacheService(null);
      await cache.set('catalog:item:1', { id: 1 }, 1);

      vi.advanceTimersByTime(1001);

      await expect(cache.get('catalog:item:1')).resolves.toBeNull();
    } finally {
      vi.useRealTimers();
    }
  });
});
