
import xml.etree.ElementTree as ET
import re
from datetime import datetime

# Define namespaces
namespaces = {
    'wp': 'http://wordpress.org/export/1.2/',
    'excerpt': 'http://wordpress.org/export/1.2/excerpt/',
    'content': 'http://purl.org/rss/1.0/modules/content/',
    'wfw': 'http://wellformedweb.org/CommentAPI/',
    'dc': 'http://purl.org/dc/elements/1.1/'
}

for prefix, uri in namespaces.items():
    ET.register_namespace(prefix, uri)

def slugify(text):
    if not text:
        return 'untitled'
    text = text.lower()
    text = re.sub(r'[^a-z0-9\s-]', '', text)
    text = re.sub(r'[\s-]+', '-', text)
    return text.strip('-')

def fix_xml(file_path):
    try:
        tree = ET.parse(file_path)
        root = tree.getroot()
        channel = root.find('channel')
    except ET.ParseError as e:
        print(f"XML Parse Error: {e}")
        return

    for item in channel.findall('item'):
        # Get Post ID for defaults
        try:
            post_id = item.find('wp:post_id', namespaces).text
        except AttributeError:
            post_id = '0'

        title_elem = item.find('title')
        title = title_elem.text if title_elem is not None else ''
        
        post_date_elem = item.find('wp:post_date', namespaces)
        post_date = post_date_elem.text if post_date_elem is not None else datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        
        # 0. Check and add excerpt:encoded
        if item.find('excerpt:encoded', namespaces) is None:
            excerpt = ET.Element('excerpt:encoded')
            excerpt.text = ''
            item.append(excerpt)

        # 0.1 Check and add content:encoded
        if item.find('content:encoded', namespaces) is None:
            content = ET.Element('content:encoded')
            content.text = ''
            item.append(content)

        # 1. Check and add GUID
        if item.find('guid') is None:
            guid = ET.Element('guid')
            guid.set('isPermaLink', 'false')
            guid.text = f'https://demo.zzprompts.com/?p={post_id}'
            item.append(guid)
            
        # 2. Check and add Link
        if item.find('link') is None:
            link = ET.Element('link')
            slug = slugify(title) if title else f'post-{post_id}'
            link.text = f'https://demo.zzprompts.com/{slug}/'
            item.append(link)

        # 3. Check and add pubDate
        if item.find('pubDate') is None:
            pubDate = ET.Element('pubDate')
            try:
                dt = datetime.strptime(post_date, '%Y-%m-%d %H:%M:%S')
                pubDate.text = dt.strftime('%a, %d %b %Y %H:%M:%S +0000')
            except:
                pubDate.text = 'Wed, 22 Jan 2026 00:00:00 +0000'
            item.append(pubDate)
            
        # 4. Check and add wp:post_date_gmt
        if item.find('wp:post_date_gmt', namespaces) is None:
            gmt = ET.Element('wp:post_date_gmt')
            gmt.text = post_date
            item.append(gmt)
            
        # 5. Check and add wp:post_name (slug)
        if item.find('wp:post_name', namespaces) is None:
            post_name = ET.Element('wp:post_name')
            post_name.text = slugify(title) if title else f'post-{post_id}'
            item.append(post_name)
            
        # 6. Check and add wp:comment_status
        if item.find('wp:comment_status', namespaces) is None:
            cs = ET.Element('wp:comment_status')
            cs.text = 'open'
            item.append(cs)
            
        # 7. Check and add wp:ping_status
        if item.find('wp:ping_status', namespaces) is None:
            ps = ET.Element('wp:ping_status')
            ps.text = 'closed'
            item.append(ps)
            
        # 8. Check and add wp:post_parent
        if item.find('wp:post_parent', namespaces) is None:
            pp = ET.Element('wp:post_parent')
            pp.text = '0'
            item.append(pp)
            
        # 9. Check and add wp:menu_order
        if item.find('wp:menu_order', namespaces) is None:
            mo = ET.Element('wp:menu_order')
            mo.text = '0'
            item.append(mo)
            
        # 10. Check and add wp:post_password
        if item.find('wp:post_password', namespaces) is None:
            ppw = ET.Element('wp:post_password')
            ppw.text = ''
            item.append(ppw)
            
        # 11. Check and add wp:is_sticky
        if item.find('wp:is_sticky', namespaces) is None:
            sticky = ET.Element('wp:is_sticky')
            sticky.text = '0'
            item.append(sticky)

    # Output the fix
    tree.write(file_path, encoding='UTF-8', xml_declaration=True)
    print(f"Successfully updated {file_path}")

if __name__ == "__main__":
    fix_xml(r'c:\laragon\www\zzprompts\wp-content\themes\zzprompts\demo-content\demo-content.xml')
