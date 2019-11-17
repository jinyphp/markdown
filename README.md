# markdown convert
지니 markdown은 마크다운 문법의 코드를 html로 변환하여 처리를 합니다.

## 헬퍼함수
지니 마크다운은 쉽게 markdown 문법을 변환처리 할 수 있는 헬퍼함수를 가지고 있습니다.

다음과 같이 `\Jiny\markdown()` 함수에 변환하고자 하는 마크다운 내용을 인자로 전달하시면 됩니다.

```php
$body = \Jiny\markdown($body);
```

## 프록시
지니 마크다운은 `erusev/parsedown` 페키지의 프록시 패키지 입니다. 향후 다양한 마크다운 페키지를 유연한 변환처리를 지원합니다.

