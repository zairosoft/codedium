export type NotificationEvent = {
  name: string;
  payload: Record<string, unknown>;
  receivedAt: Date;
};

export interface NotificationServicePort {
  record(event: NotificationEvent): Promise<void>;
}

export const NOTIFICATION_SERVICE = Symbol('NOTIFICATION_SERVICE');
