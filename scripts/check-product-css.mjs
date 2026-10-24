/** Validate the versioned current-product CSS graph, including compatibility aliases. */
import { readFileSync, existsSync } from 'node:fs';
import { resolve } from 'node:path';
import { createHash } from 'node:crypto';
import postcss from 'postcss';
const root=process.cwd(), map=JSON.parse(readFileSync('deployment/product-css-map.json','utf8'));
const files=new Set([...map.managed_stylesheets,...map.shared_groups.map(g=>g.path),...map.inline_stylesheets.map(g=>g.path)]);
const sha=b=>createHash('sha256').update(b).digest('hex');
const fail=s=>{throw new Error(s)};
for(const path of files){
 if(!path.endsWith('.css')||path.startsWith('/')||path.split('/').some(p=>p==='..'||p.startsWith('.')))fail('Unsafe CSS map path');
 const text=readFileSync(resolve(root,'legacy-public',path),'utf8');postcss.parse(text,{from:path});
}
for(const group of map.shared_groups){
 const value=readFileSync(resolve(root,'legacy-public',group.path));
 if(sha(value)!==group.sha256)fail('Shared stylesheet hash differs from adopted map');
 if(/url\s*\(|@import/i.test(value.toString()))fail('Shared stylesheet now needs a resource-base review');
 for(const alias of group.aliases){
  const text=readFileSync(resolve(root,'legacy-public',alias),'utf8').trim();
  if(text!==`@import url("/${group.path}");`)fail('Compatibility alias does not point to fixed shared CSS');
 }
}
for(const path of map.font_fallback_stylesheets){
 const ast=postcss.parse(readFileSync(resolve(root,'legacy-public',path),'utf8'));
 let fallback=false;ast.walkRules(rule=>{if(rule.selector==='html.wf-inactive')rule.walkDecls('visibility',d=>{if(d.value==='visible')fallback=true})});
 if(!fallback)fail('Font failure visibility fallback missing');
}
const release=JSON.parse(readFileSync('deployment/legacy-files.json','utf8')).files;
for(const f of files)if(!release.includes(f))fail('Managed CSS missing from release manifest');
console.log(`Product CSS verified: ${files.size} stylesheets, ${map.shared_groups.length} shared groups, ${map.font_fallback_stylesheets.length} font fallbacks.`);
