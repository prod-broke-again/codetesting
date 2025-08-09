import hljs from 'highlight.js';
import 'highlight.js/styles/github-dark.css';

export function hljsLanguageClass(lang?: string): string {
  const map: Record<string, string> = {
    php: 'php', javascript: 'javascript', typescript: 'typescript', python: 'python', java: 'java', cpp: 'cpp', csharp: 'csharp', html: 'xml', css: 'css', sql: 'sql', bash: 'bash', json: 'json', xml: 'xml', markdown: 'markdown', vue: 'vue', jsx: 'javascript', tsx: 'typescript', blade: 'php', 'php-html': 'php', 'php-blade': 'php', 'html-css': 'xml', 'html-js': 'xml'
  };
  const key = (lang || '').toString().toLowerCase();
  return map[key] ? `language-${map[key]}` : '';
}

export function highlightAllIn(container?: HTMLElement | Document): void {
  const root: any = container || document;
  root.querySelectorAll('code.hljs').forEach((el: Element) => hljs.highlightElement(el as HTMLElement));
}
