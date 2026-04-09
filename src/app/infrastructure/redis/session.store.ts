import Redis from 'ioredis';

export interface SessionStore {
  get<T>(sessionId: string): Promise<T | null>;
  set<T>(sessionId: string, payload: T, ttlSeconds?: number): Promise<void>;
  destroy(sessionId: string): Promise<void>;
}

type InMemorySession = {
  payload: string;
  expiresAt?: number;
};

export class InMemorySessionStore implements SessionStore {
  private readonly sessions = new Map<string, InMemorySession>();

  async get<T>(sessionId: string): Promise<T | null> {
    const found = this.sessions.get(sessionId);
    if (!found) {
      return null;
    }

    if (found.expiresAt && found.expiresAt < Date.now()) {
      this.sessions.delete(sessionId);
      return null;
    }

    return JSON.parse(found.payload) as T;
  }

  async set<T>(sessionId: string, payload: T, ttlSeconds = 3600): Promise<void> {
    this.sessions.set(sessionId, {
      payload: JSON.stringify(payload),
      expiresAt: Date.now() + ttlSeconds * 1000,
    });
  }

  async destroy(sessionId: string): Promise<void> {
    this.sessions.delete(sessionId);
  }
}

export class RedisSessionStore implements SessionStore {
  constructor(private readonly redis: Redis) {}

  async get<T>(sessionId: string): Promise<T | null> {
    const raw = await this.redis.get(`session:${sessionId}`);
    return raw ? (JSON.parse(raw) as T) : null;
  }

  async set<T>(sessionId: string, payload: T, ttlSeconds = 3600): Promise<void> {
    await this.redis.set(`session:${sessionId}`, JSON.stringify(payload), 'EX', ttlSeconds);
  }

  async destroy(sessionId: string): Promise<void> {
    await this.redis.del(`session:${sessionId}`);
  }
}


