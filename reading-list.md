---
title: 所有归纳
subtitle: 文章目录
layout: "page"
icon: fa-book
order: 3
---
<ul>
  {% for post in site.posts %}
    <li>
      <a href="{{ post.url }}">{{ post.title }}</a>
      {{ post.excerpt }}
    </li>
  {% endfor %}
</ul>
