export type NotificationEventModel = {
  name: string;
  payload: Record<string, unknown>;
  receivedAt: Date;
};
