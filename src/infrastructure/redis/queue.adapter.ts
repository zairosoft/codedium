import { Queue } from 'bullmq';

export interface QueueAdapter {
  enqueue(queueName: string, jobName: string, payload: unknown): Promise<void>;
  close?(): Promise<void>;
}

export type QueueConnection = {
  host: string;
  port: number;
  password?: string;
  db?: number;
};

export class InMemoryQueueAdapter implements QueueAdapter {
  private readonly storage = new Map<string, unknown[]>();

  async enqueue(queueName: string, _jobName: string, payload: unknown): Promise<void> {
    const existing = this.storage.get(queueName) ?? [];
    existing.push(payload);
    this.storage.set(queueName, existing);
  }
}

export class RedisQueueAdapter implements QueueAdapter {
  private readonly queues = new Map<string, Queue>();

  constructor(private readonly connection: QueueConnection) {}

  async enqueue(queueName: string, jobName: string, payload: unknown): Promise<void> {
    let queue = this.queues.get(queueName);
    if (!queue) {
      queue = new Queue(queueName, {
        connection: this.connection,
      });
      this.queues.set(queueName, queue);
    }

    await queue.add(jobName, payload);
  }

  async close(): Promise<void> {
    await Promise.all(
      Array.from(this.queues.values()).map((queue) => queue.close()),
    );
  }
}

