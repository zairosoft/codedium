import { Injectable } from '@nestjs/common';
import { EventEmitter2 } from '@nestjs/event-emitter';

@Injectable()
export class EventBusService {
  constructor(private readonly eventEmitter: EventEmitter2) {}

  async emit<T>(eventName: string, payload: T): Promise<void> {
    await this.eventEmitter.emitAsync(eventName, payload);
  }
}
