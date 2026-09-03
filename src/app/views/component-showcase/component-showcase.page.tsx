import type { ReactNode } from 'react';
import { BadgeBasic } from '@/app/views/components/badges/badge-basic';
import { BadgeDot } from '@/app/views/components/badges/badge-dot';
import { BadgeGlow } from '@/app/views/components/badges/badge-glow';
import { BadgeOutlined } from '@/app/views/components/badges/badge-outlined';
import { BadgeRounded } from '@/app/views/components/badges/badge-rounded';
import { BadgeSoftDot } from '@/app/views/components/badges/badge-soft-dot';
import { BadgeSoft } from '@/app/views/components/badges/badge-soft';
import type { BadgeTone } from '@/app/views/components/badges/badge.types';
import { Button } from '@/app/views/components/buttons/button';
import { renderMainLayoutView } from '@/app/views/components/layouts/layout';

const badgeTones: Array<{ label: string; tone: BadgeTone }> = [
  { label: 'Default', tone: 'default' },
  { label: 'Primary', tone: 'primary' },
  { label: 'Secondary', tone: 'secondary' },
  { label: 'Info', tone: 'info' },
  { label: 'Success', tone: 'success' },
  { label: 'Warning', tone: 'warning' },
  { label: 'Error', tone: 'error' },
  { label: 'Dark', tone: 'dark' },
  { label: 'Light', tone: 'light' },
];

type ShowcaseCardProps = {
  title: string;
  description: string;
  children: ReactNode;
};

function ShowcaseCard({ title, description, children }: ShowcaseCardProps) {
  return (
    <section className="card px-4 pb-4 sm:px-5">
      <div className="my-3 flex min-h-8 items-center">
        <h2 className="font-medium tracking-wide text-slate-700 dark:text-navy-100 lg:text-base">
          {title}
        </h2>
      </div>
      <p className="max-w-2xl text-sm text-slate-500 dark:text-navy-200">
        {description}
      </p>
      <div className="mt-5 flex flex-wrap items-center gap-2.5">
        {children}
      </div>
    </section>
  );
}

export function renderComponentShowcasePage(): string {
  return renderMainLayoutView({
    title: 'Components',
    content: (
      <div className="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
        <section id="badges" className="scroll-mt-20">
          <div className="mb-4">
            <p className="text-xs font-semibold uppercase tracking-[0.18em] text-accent">Components</p>
            <h2 className="mt-1 text-lg font-semibold text-slate-800 dark:text-navy-50">Badges</h2>
          </div>

          <div className="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
            <ShowcaseCard title="Badge" description="Basic badges for labels and compact status values.">
              {badgeTones.map(({ label, tone }) => (
                <BadgeBasic key={tone} tone={tone}>{label}</BadgeBasic>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Rounded Badge" description="Badges with a fully rounded pill shape.">
              {badgeTones.map(({ label, tone }) => (
                <BadgeRounded key={tone} tone={tone}>{label}</BadgeRounded>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Glow Badge" description="Solid badges with a soft color-matched shadow.">
              {badgeTones.slice(0, 7).map(({ label, tone }) => (
                <BadgeGlow key={tone} tone={tone}>{label}</BadgeGlow>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Soft Color Badge" description="Low-emphasis badges with a tinted background.">
              {badgeTones.slice(1, 7).map(({ label, tone }) => (
                <BadgeSoft key={tone} tone={tone}>{label}</BadgeSoft>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Outlined Badge" description="Rounded badges with a colored outline and transparent background.">
              {badgeTones.map(({ label, tone }) => (
                <BadgeOutlined key={tone} tone={tone}>{label}</BadgeOutlined>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Badge With Dots" description="Minimal status badges represented by a colored dot and label.">
              {badgeTones.slice(0, 7).map(({ label, tone }) => (
                <BadgeDot key={tone} tone={tone}>{label}</BadgeDot>
              ))}
            </ShowcaseCard>

            <ShowcaseCard title="Soft Color Badge With Dots" description="Rounded soft badges with a matching status dot.">
              {badgeTones.slice(1, 7).map(({ label, tone }) => (
                <BadgeSoftDot key={tone} tone={tone}>{label}</BadgeSoftDot>
              ))}
            </ShowcaseCard>
          </div>
        </section>

        <section id="buttons" className="scroll-mt-20">
          <div className="mb-4">
            <p className="text-xs font-semibold uppercase tracking-[0.18em] text-accent">Components</p>
            <h2 className="mt-1 text-lg font-semibold text-slate-800 dark:text-navy-50">Buttons</h2>
          </div>
          <ShowcaseCard title="Button" description="Shared button variants available to server-rendered views.">
            <Button variant="primary">Primary</Button>
            <Button variant="secondary">Secondary</Button>
            <Button variant="danger">Danger</Button>
            <Button variant="outline">Outline</Button>
          </ShowcaseCard>
        </section>
      </div>
    ),
  });
}
